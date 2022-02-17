<?php
    use Predis\Pipeline\Pipeline;
class PredisLibrary{
        private $client;
        private $redisServerConfig;
        private $CI;
        private $pipeLine;
        private $enablePipeline;
        private static $predisLibrary;
        private $redisIsAlive = FALSE;
        private function __construct() {
            $this->CI = &get_instance();
            $this->CI->config->load('redis');
            $this->redisServerConfig = $this->CI->config->item('redis_server');
            //$this->enablePipeline = $this->CI->config->item('EnablePipeline');
            $this->enablePipeline = TRUE;
            require_once APPPATH.'third_party/Predis/autoload.php';
            Predis\Autoloader::register();
            $this->client = new Predis\Client(array(    'scheme'    => 'tcp',
                                                        'host'      => $this->redisServerConfig['host'],
                                                        'port'      => $this->redisServerConfig['port'],
                                                        'database'  => $this->redisServerConfig['database']
                                                    )
                                            );
            $this->redisIsAlive = $this->client->getRedisStatus();
        }
        
        public static function getInstance(){
        	if(static::$predisLibrary === null){
        		$s = microtime(true);
                global $isMobileApp;
                static::$predisLibrary = new PredisLibrary();
                if((microtime(true)-$s) > 0.1) error_log("\n".date("d-m-y h:i:s")." Inside getInstance ".(microtime(true)-$s)." , Mobile : ".$isMobileApp, 3, '/tmp/perfLogs.log');
        	}
        	return static::$predisLibrary;
        }
        
        public function isRedisAlive(){
        	if($this->redisIsAlive > 0){
        		return TRUE;
        	}else{
        		return FALSE;
        	}
        }
        
        public function setPipeLine(){
        	$this->pipeLine = $this->client->pipeline();
        }
        
        public function executePipeLine(){
        	if($this->pipeLine instanceof Pipeline   && $this->enablePipeline){
        		$resposne = $this->pipeLine->execute();
        		// $this->client->disconnect();
        		// static::$predisLibrary = null;
        		return $resposne;
        	}
        	return FALSE;
        }
        
        public function infoRedis($section){
        	Contract::mustBeNonEmptyVariable($section, 'Section');
        	return $this->client->info($section);
        }
        
        /** Functions that apply on all Data Structures Starts **/
        public function expireKey($key,$seconds,$inserInPipeLine = FALSE){
            Contract::mustBeNumericValueGreaterThanZero($seconds, 'Expire Time For Key');
            if($inserInPipeLine  && $this->enablePipeline){
            	if (!$this->pipeLine instanceof Pipeline  && $this->enablePipeline)
            		$this->setPipeLine();
            	$this->pipeLine->expire($key, $seconds);
            }else{
            	return $this->client->expire($key, $seconds);
            }
        }
        
        public function checkIfKeyExists($key){
        	return $this->client->exists($key);
        }
        
        public function checkTypeOfKey($key){
        	return $this->client->type($key);
        }
        
        public function deleteKey($keys=array(), $inserInPipeLine = FALSE){
        	Contract::mustBeNonEmptyArray($keys, 'Keys To Delete');
        	if($inserInPipeLine  && $this->enablePipeline){
        		if(!$this->pipeLine instanceof Pipeline)
        			$this->setPipeLine();
        		$this->pipeLine->del($keys);
        	}else{
        		return $this->client->del($keys);
        	}
        }
        
        public function renameKey($key,$newKey,$renameWhenNewKeyDoesNotExist = TRUE){
        	Contract::mustBeNonEmptyVariable($key, 'Rename Source Key');
        	Contract::mustBeNonEmptyVariable($newKey, 'Rename Target Key');
        	if($renameWhenNewKeyDoesNotExist){
        		return $this->client->renamenx($key, $newKey);
        	}else{
        		return $this->client->rename($key, $newKey);
        	}
        }

        public function fetchKeysBasedOnPattern($pattern){
            Contract::mustBeNonEmptyVariable($pattern, 'Seacrh Pattern');
            return $this->client->keys($pattern);
        }

        public function getKeysTTL($key){
            Contract::mustBeNonEmptyVariable($key, "String Key");            
            return $this->client->ttl($key);
        }

        public function removeTTLFromKey($key){
            Contract::mustBeNonEmptyVariable($key, "String Key");            
            return $this->client->persist($key);
        }
        
        /** Functions that apply on all Data Structures Ends **/
        
        /** Functions that apply on Strings Starts **/
        public function addMemberToString($key, $member, $expireInSeconds = 0, $addOnlyIfKeyNotExist = FALSE, $insertInPipeLine = FALSE){
        	Contract::mustBeNonEmptyVariable($key, "String Key");
        	Contract::mustBeNonEmptyVariable($member, "String Value");
        	if($insertInPipeLine && $this->enablePipeline){
        		if(!$this->pipeLine instanceof Pipeline)
        			$this->setPipeLine();
        		if($addOnlyIfKeyNotExist){
        			$this->pipeLine->setnx($key, $member);
        		}else{
        			$this->pipeLine->set($key, $member);
        		}
        		if($expireInSeconds > 0){
        			$this->pipeLine->expire($key, $expireInSeconds);
        		}
        		return  true;
        	}else{
        		if($addOnlyIfKeyNotExist){
        			$this->client->setnx($key, $member);
        		}else{
        			$this->client->set($key, $member);
        		}
        		if($expireInSeconds){
        			$this->client->expire($key, $expireInSeconds);
        		}
        		return true;
        	}
        }
        
        public function getMemberOfString($key){
            Contract::mustBeNonEmptyVariable($key, "String Key");
            $value = $this->client->get($key);
            return $value;
        }

        /**
        *  Function to get Values for Multiple String from Redis
        *  @param : $keysArray 1D Array With keys names
        *   
        *  @return : 1D Array with values of keys in the same order
        *  @Usage  : $this->redisLib->getMemberOfMultipleString(array("test1","test2","test3"));
        *  @Output : Array
        *            (
        *                [0] => test1value
        *                [1] => test2value
        *                [2] => test3value
        *            )
        *
        */
        public function getMemberOfMultipleString($keysArray){
            Contract::mustBeNonEmptyArray($keysArray, "String Keys Array");
            $value = $this->client->mget($keysArray);
            
            return $value;
        }
        
        public function setBit($key, $offset, $value, $insertInPipeline = FALSE){
        	Contract::mustBeNonEmptyVariable($key, 'Key Name');
        	Contract::mustBeNumericValueGreaterThanZero($offset, 'Bit Offset');
        	Contract::mustBeNumericValue($value, 'Bit Value');
        	if($insertInPipeline && $this->enablePipeline){
        		if(!$this->pipeLine instanceof Pipeline)
        			$this->setPipeLine();
        		$this->pipeLine->setbit($key, $offset, $value);
        	}else{
        		return $this->client->setbit($key, $offset, $value);
        	}
        }
        
        public function getBit($key, $offset, $insertInPipeline = FALSE){
        	Contract::mustBeNonEmptyVariable($key, 'Key Name');
        	Contract::mustBeNumericValueGreaterThanZero($offset, 'Bit Offset');
        	if($insertInPipeline && $this->enablePipeline){
        		if(!$this->pipeLine instanceof Pipeline)
        			$this->setPipeLine();
        		$this->pipeLine->getbit($key, $offset);
        	}else{
        		return $this->client->getbit($key, $offset);
        	}
        }
        
        /** Functions that apply on String Ends **/
        
        
        
        /** Functions related To SET Data structure  Starts **/
        public function addMembersToSet($key,$members = array(), $insertInPipeLine = FALSE){
        	Contract::mustBeNonEmptyVariable($key, 'Key Name');
        	Contract::mustBeNonEmptyArray($members, 'Members for Set');
        	if($insertInPipeLine && $this->enablePipeline){
        		if(!$this->pipeLine instanceof Pipeline)
        			$this->setPipeLine();
        		$this->pipeLine->sadd($key, $members);
        	}else{
        		return $this->client->sadd($key,$members);
        	}
        }
        
        public function getMembersOfSet($key){
        	Contract::mustBeNonEmptyVariable($key, 'Key Name');
        	return  $this->client->smembers($key);
        }
        
        public function removeMembersOfSet($key, $members=array(), $insertInPipeLine = FALSE){
        	Contract::mustBeNonEmptyVariable($key, 'Key Name');
        	Contract::mustBeNonEmptyArray($members, 'Members for Set');
        	if($insertInPipeLine && $this->enablePipeline){
        		if(!$this->pipeLine instanceof Pipeline)
        			$this->setPipeLine();
        		$this->pipeLine->srem($key, $members);
        	}else{
        		return $this->client->srem($key, $members);
        	}
        }
        
        public function getMembersCountOfSet($key){
        	Contract::mustBeNonEmptyVariable($key, 'Key Name');
        	return $this->client->scard($key);
        }

        public function checkIfMemberOfSet($key,$member){
        	Contract::mustBeNonEmptyVariable($key, 'Key Name');
        	Contract::mustBeNonEmptyVariable($member, 'Set Member');
        	return $this->client->sismember($key, $member);
        }
        
        public function subtractSetFromOtherSets($key, $diffKeys = array(), $store = FALSE, $destinationKey){
        	Contract::mustBeNonEmptyArray($diffKeys, 'Set Diff Keys');
        	$keysForDiff = array_unshift($diffKeys, $key);
        	if($store){
        		Contract::mustBeNonEmptyVariable($destinationKey, 'Set Diff Destination Key');
        		return $this->client->sdiffstore($destinationKey, $keysForDiff);
        	}else{
        		return $this->client->sdiff($keysForDiff);
        	}
        }
        
        public function moveMemberBetweenSets($key, $destinationKey, $member){
        	Contract::mustBeNonEmptyVariable($key, 'Set Source Key');
        	Contract::mustBeNonEmptyVariable($key, 'Set Destination Key');
        	Contract::mustBeNonEmptyVariable($key, 'Set Member');
        	return $this->client->smove($key, $destinationKey, $member);
        }
        
        public function intersectionOfSets($key, $interSectionKeys = array(), $store = FALSE, $destinationKey){
        	Contract::mustBeNonEmptyArray($interSectionKeys, 'Set Intersection Keys');
        	$keysForIntersection = array_unshift($interSectionKeys, $key);
        	if($store){
        		Contract::mustBeNonEmptyVariable($destinationKey, 'Set Intersection Destination Key');
        		return $this->client->sinterstore($destinationKey, $keysForIntersection);
        	}else{
        		return $this->client->sinter($keysForIntersection);
        	}
        }
        
        public function unionOfSets($key, $unionKeys = array(),$store = FALSE, $destinationKey){
        	Contract	::mustBeNonEmptyArray($unionKeys, 'Set Union Keys');
        	$keysForUnion = array_unshift($unionKeys, $key);
        	if($store){
        		Contract::mustBeNonEmptyVariable($destinationKey, 'Set Union Destination Key');
        		return $this->client->sunionstore($destinationKey, $keysForUnion);
        	}else{
        		return $this->client->sunion($keysForUnion);
        	}
        }
        
        /** Functions related To SET Data structure  Ends **/
        
        
        
        /** Functions related to SORTED SET Data Structure Starts **/
        public function addMembersToSortedSet($key, $members=array(), $insertInPipeLine = FALSE){
        	Contract::mustBeNonEmptyArray($members, 'Sorted Set Members');
        	if ($insertInPipeLine && $this->enablePipeline){
        		if(!$this->pipeLine instanceof Pipeline)
        			$this->setPipeLine();
        		$this->pipeLine->zadd($key, $members);
        	}else{
        		return $this->client->zadd($key, $members);
        	}
        }
        
        public function getNumberOfMembersInSortedSet($key){
        	return $this->client->zcard($key);
        }
        
        public function getMembersInSortedSet($key,$start,$stop,$reverseOrder = FALSE, $withScore = TRUE,$insertInPipeLine = FALSE){
        	Contract::mustBeNumericValue($start, 'Sorted Set Start Index');
        	Contract::mustBeNumericValue($stop, 'Sorted Set Stop Index');
        	if($withScore){
        		$options = 'WITHSCORES';
        	}else{
        		$options[] = null;
        	}
            if ($insertInPipeLine && $this->enablePipeline){
                if(!$this->pipeLine instanceof Pipeline)
                    $this->setPipeLine();
                if($reverseOrder){
                    return $this->pipeLine->zrevrange($key, $start, $stop, $options);
                }else{
                    return $this->pipeLine->zrange($key, $start, $stop, $options);
                }
            }
        	else if($reverseOrder){
        		return $this->client->zrevrange($key, $start, $stop, $options);
        	}else{
        		return $this->client->zrange($key, $start, $stop, $options);
        	}
        }
        
        public function removeMembersInSortedSet($key, $members=array(), $insertInPipeLine = FALSE){
        	Contract::mustBeNonEmptyVariable($key, 'Sorted Set Key');
        	Contract::mustBeNonEmptyArray($members, 'Sorted Set Members');
        	if($insertInPipeLine && $this->enablePipeline){
        		if (!$this->pipeLine instanceof Pipeline)
        			$this->setPipeLine();
        		$this->pipeLine->zrem($key, $members);
        	}else{
        		$this->client->zrem($key, $members);
        	}
        }
        
        public function removeMembersInSortedSetByScore($key, $min, $max, $insertInPipeLine = FALSE){
        	Contract::mustBeNonEmptyVariable($key, 'Sorted Set Key');
        	if($insertInPipeLine && $this->enablePipeline){
        		if(!$this->pipeLine instanceof Pipeline)
        			$this->setPipeLine();
        		$this->pipeLine->zremrangebyscore($key, $min, $max);
        	}else {
        		$this->client->zremrangebyscore($key, $min, $max);
        	}
        }
        
        public function getScoreOfMemberInSortedSet($key,$member){
        	Contract::mustBeNonEmptyVariable($key, 'Sorted Set Key');
        	Contract::mustBeNonEmptyVariable($key, 'Sorted Set Member');
        	return $this->client->zscore($key, $member);
        }
        
        public function getMembersByRangeInSortedSet($key,$min,$max,$reverseOrder = FALSE, $withScore = TRUE){
        	Contract::mustBeNonEmptyVariable($key, 'Sorted Set Key');
        	Contract::mustBeNonEmptyVariable($min, 'Sorted Set Min Range');
        	Contract::mustBeNonEmptyVariable($max, 'Sorted Set Max Range');
        	if($withScore){
        		$options = 'WITHSCORES';
        	}else{
        		$options[] = null;
        	}
        	if($reverseOrder){
        		return $this->client->zrevrangebyscore($key, $max, $min, $options);
        	}else{
        		return $this->client->zrangebyscore($key, $min, $max, $options);
        	}
        }
        
        public function incrementValueOfMembersOfSortedSet($key, $membersWithValue = array(), $insertInPipeLine = FALSE){
        	Contract::mustBeNonEmptyVariable($key, 'Sorted Set Key');
        	Contract::mustBeNonEmptyArray($membersWithValue, 'Sorted Set Members For Increment');
        	if($insertInPipeLine && $this->enablePipeline){
        		if(!$this->pipeLine instanceof Pipeline)
        			$this->setPipeLine();
        		foreach ($membersWithValue as $member => $increment){
        			$this->pipeLine->zincrby($key, $increment, $member);
        		}
        	}else{
	        	$pipe	= $this->client->pipeline();
	        	foreach ($membersWithValue as $member => $increment){
	        		$pipe->zincrby($key, $increment, $member);
	        	}
	        	$pipe->execute();
        	}
        	return ;
        }
        
        /** Functions related to SORTED SET Data Structure Ends **/
        
        
        
        /** Functions related To LIST Data structure Starts **/
        public function leftPushInList($key, $values=array(), $pushIfKeyExist = FALSE, $inserInPipeLine = FALSE){
        	Contract::mustBeNonEmptyArray($values, 'Members for List');
        	if($inserInPipeLine  && $this->enablePipeline){
        		if(!$this->pipeLine instanceof Pipeline)
        			$this->setPipeLine();
        		if($pushIfKeyExist){
        			$this->pipeLine->lpushx($key, $values);
        		}else{
        			$this->pipeLine->lpush($key, $values);
        		}
        	}else{
	        	if($pushIfKeyExist){
	        		return $this->client->lpushx($key, $values);
	        	}else{
	        		return $this->client->lpush($key, $values);
	        	}
        	}
        }
        
        public function rightPushInList($key,$values=array(),$pushIfKeyExist = FALSE, $insertInPipeLine = FALSE){
        	Contract::mustBeNonEmptyArray($values, 'Members for List');
        	if($insertInPipeLine && $this->enablePipeline){
        		if(!$this->pipeLine instanceof Pipeline)
        			$this->setPipeLine();
        		if($pushIfKeyExist){
        			$this->pipeLine->rpushx($key, $values);
        		}else{
        			$this->pipeLine->rpush($key, $values);
        		}
        	}else{
        		if($pushIfKeyExist){
        			$this->client->rpushx($key, $values);
	        	}else{
	        		$this->client->rpush($key, $values);
	        	}
        	}
        }
        
        public function leftPopFromList($key){
        	return $this->client->lpop($key);
        }
        
        public function rightPopFromList($key){
        	return $this->client->rpop($key);
        }
        
        public function getMembersOFList($key,$start=0,$stop=0){
        	Contract::mustBeNumericValue($start, 'List Start Index');
        	Contract::mustBeNumericValue($stop, 'List Stop Index');
        	return $this->client->lrange($key, $start, $stop);
        }
        
        public function getLengthOfList($key){
        	return $this->client->llen($key);
        }
        
        public function removeMembersFromListByValue($key, $count=0, $value, $insertInPipeLine = FALSE){
        	Contract::mustBeNumericValue($count, 'Count for items to remove');
        	if($insertInPipeLine  && $this->enablePipeline){
        		if(!$this->pipeLine instanceof Pipeline)
        			$this->setPipeLine();
        		$this->pipeLine->lrem($key, $count, $value);
        	}else{
        		return $this->client->lrem($key, $count, $value);
        	}
        }
        
        public function trimListByIndex($key,$start,$stop){
        	Contract::mustBeNumericValue($start, 'List Start Index');
        	Contract::mustBeNumericValue($stop, 'List Stop Index');
        	return $this->client->ltrim($key, $start, $stop);
        }
        
        /** Functions related To LIST Data structure Ends **/
        
        
        
        /** Functions related to Hash Data Structure Starts **/
        public function addMembersToHash($key,$members=array(),$addMemberIfNotExist = FALSE, $insertInPipeLine = FALSE){
        	Contract::mustBeNonEmptyArray($members, 'Hash Members');
        	if($insertInPipeLine  && $this->enablePipeline){
        		if(!$this->pipeLine instanceof Pipeline)
        			$this->setPipeLine();
        		if($addMemberIfNotExist){
        			foreach ($members as $field=>$value){
        				$this->pipeLine->hsetnx($key, $field, $value);
        			}
        		}else{
        			$this->pipeLine->hmset($key, $members);
        		}
        	}else{
	        	if($addMemberIfNotExist){
	        		$pipe = $this->client->pipeline();
	        		foreach ($members as $field=>$value){
	        			$pipe->hsetnx($key, $field, $value);
	        		}
	        		$pipe->execute();
	        	}else{
	        		return $this->client->hmset($key, $members);
	        	}
        	}
        }
        
        public function addMemberToHashIfNotExist($key, $field, $value, $insertInPipeLine = FALSE){
        	if($insertInPipeLine  && $this->enablePipeline){
        		if (!$this->pipeLine instanceof Pipeline)
        			$this->setPipeLine();
        		$this->pipeLine->hsetnx($key, $field, $value);
        	}else{
        		return $this->client->hsetnx($key, $field, $value);
        	}
        }
        
        public function getMembersOfHashByFieldNameWithValue($key,$fieldName = array(), $insertInPipeLine = FALSE){
        	Contract::mustBeNonEmptyArray($fieldName, 'Hash Members');
            if($insertInPipeLine  && $this->enablePipeline){
                if(!$this->pipeLine instanceof Pipeline)
                    $this->setPipeLine();
                
                $this->pipeLine->hmget($key, $fieldName);
            }else{
            	$response = $this->client->hmget($key, $fieldName);
            	$i=0;$finalResponse = array();
            	foreach($fieldName as $value){
            		$finalResponse[$value] = $response[$i++];
            	}
            	return $finalResponse;
            }
        }
        
        public function getAllMembersOfHashWithValue($key, $insertInPipeLine = FALSE){
        	if($insertInPipeLine  && $this->enablePipeline){
                if(!$this->pipeLine instanceof Pipeline)
                    $this->setPipeLine();
                
                $this->pipeLine->hgetall($key);
            }else{
                return $this->client->hgetall($key);
            }
        }
        
        public function getMembersFieldNameOfHash($key){
        	return $this->client->hkeys($key);
        }
        
        public function deleteMembersOfHash($key,$fields=array(), $insertInPipeLine = FALSE){
        	Contract::mustBeNonEmptyArray($fields, 'Hash Members');
        	if($insertInPipeLine  && $this->enablePipeline){
        		if(!$this->pipeLine instanceof Pipeline)
        			$this->setPipeLine();
        		$this->pipeLine->hdel($key, $fields);
        	}else{
        		return $this->client->hdel($key, $fields);
        	}
        }
        
        public function checkIfMemberOfHash($key,$fieldName){
        	return $this->client->hexists($key, $fieldName);
        }
        
        public function getNumberOfMembersInHash($key){
        	return $this->client->hlen($key);
        }
        
        public function incrementByFloatFieldValueOfHash($key, $field, $increment, $insertInPipeLine = FALSE){
        	if($insertInPipeLine  && $this->enablePipeline){
        		if(!$this->pipeLine instanceof Pipeline)
        			$this->setPipeLine();
        		$this->pipeLine->hincrbyfloat($key, $field, $increment);
        	}else{
        		return $this->client->hincrbyfloat($key, $field, $increment);
        	}
        }
        
        public function incrementByIntegerFieldValueOfHash($key, $field, $increment, $insertInPipeLine = FALSE){
        	if($insertInPipeLine  && $this->enablePipeline){
        		if(!$this->pipeLine instanceof Pipeline)
        			$this->setPipeLine();
        		$this->pipeLine->hincrby($key, $field, $increment);
        	}else{
        		$this->client->hincrby($key, $field, $increment);
        	}
        }
        
        /** Functions related to Hash Data Structure Ends **/

        public function incrementMemberOfString($key, $insertInPipeline = FALSE){
            Contract::mustBeNonEmptyVariable($key, 'Key Name');
            if($insertInPipeline && $this->enablePipeline){
                if(!$this->pipeLine instanceof Pipeline)
                    $this->setPipeLine();
                $this->pipeLine->incr($key);
            }else{
                return $this->client->incr($key);
            }
        }

        public function bgrewriteAOF(){
            return $this->client->bgrewriteaof();
        }
        
            
    }


?>
