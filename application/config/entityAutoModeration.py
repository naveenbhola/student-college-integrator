import nltk
from nltk import tokenize
from fuzzywuzzy import fuzz
import re
import sys
import json


regex    = re.compile('[^a-zA-Z.]')
regexNew = re.compile(r'\b[a-zA-Z]+\b')

def sentenceCleaning(sentence):
	p = re.sub(r"(\s+\.)",".",sentence) #remove extra spaces before dot ( . )
	p = re.sub(r"(\.\s+)",". ",p) #remove spaces after dot ( . )
	p = re.sub(r"(\.\s+\()",". (",p) #remove extra spaces between dot (.) and open bracket (. ( )
	p = re.sub(r"(\.\s+\))",". )",p) #remove extra spaces between dot (.) and close bracket ( ) )
	p = re.sub(r"(\(\s+)","( ",p) #remove extra spaces after open bracket ( ( )
	p = re.sub(r"(\s+\))"," )",p) #remove extra spaces before close bracket ( ) )
	#p = re.sub(r"(,(\s+)*)",", ",p) #remove extra spaces after comman ( , )
	#p = re.sub(r"(,)"," , ",p) #remove extra spaces before comman ( , )
	p = p.strip()
	return p

def ngrams(input, n):
	input = input.split(' ')
	output = []
	for i in range(len(input)-n+1):
		output.append(" ".join(input[i:i+n]))
	return output


def helper(dic, match):
    word = match.group(0)
    return dic.get(word, word)

def word_in(word, phrase, replaceWord):
	d = {word:replaceWord}
	return re.sub(r'\b(%s)\b' % '|'.join(d.keys()), lambda m:d.get(m.group(1), m.group(1)), phrase)
	#return re.sub(r'\b(\w+)\b', lambda m:d.get(m.group(1), m.group(1)), phrase)

def upperfirst(x):
    return x[0].upper() + x[1:]

def capitalizedFirtLetterOfSentence(sentences):
	count = 0
	for sen in sentences:
		sen = sen.strip('"')
		if count > 0 :
			if sen.endswith('.'):
				finalSentence  = str(finalSentence) + ' '+str(upperfirst(sen))
			else:
				finalSentence  = str(finalSentence) + ' '+str(upperfirst(sen)) + '.'
		else:
			if sen.endswith('.'):
				finalSentence  = upperfirst(sen)
			else:
				finalSentence  = upperfirst(sen) + '.'
		count +=1
	return finalSentence

def matchData(ngramData, entityToMatchWith, ngramType):
	global orginalString
	for matchingData in entityToMatchWith: #[ba,mba,bdms,btech]
		index = entityToMatchWith.index(matchingData) #[0,1,2,3]
		for tokens in ngramData:
			flag = 'true'
			for elements in tokens:
				actualToken = elements #b.a
				plainToken  = ''.join(e for e in elements if e.isalnum()) #ba
				matchingRatio = fuzz.ratio(matchingData.lower(), plainToken.lower())
				if matchingRatio == 100:
					actualToken = actualToken.replace('(', 'openbracket')
					actualToken = actualToken.replace(')', 'closebracket')
					orginalString = orginalString.replace('(', 'openbracket')
					orginalString = orginalString.replace(')', 'closebracket')
					if actualToken.endswith('.') and actualEntity[index].endswith('.') and (len(tokens)-1) == tokens.index(actualToken):
						orginalString = re.sub('(^)' +actualToken+'(\s)', actualEntity[index]+' ', orginalString)
						orginalString = re.sub('(\s)'+actualToken+'(\s)', ' '+actualEntity[index]+' ', orginalString)
					elif actualToken.endswith('.') and not actualEntity[index].endswith('.') and (len(tokens)-1) == tokens.index(actualToken):
						orginalString = re.sub('(^)' +actualToken+'(\s)', actualEntity[index]+' ', orginalString)
						orginalString = re.sub('(\s)'+actualToken+'(\s)', ' '+actualEntity[index]+'. ', orginalString)
					else:
						orginalString = re.sub('(^)' +actualToken+'(\s)', actualEntity[index]+' ', orginalString)
						orginalString = re.sub('(\s)'+actualToken+'(\s)', ' '+actualEntity[index]+' ', orginalString)

					orginalString = re.sub('(\s)'+actualToken+'(\.)', ' '+actualEntity[index]+'.', orginalString)
                                        orginalString = re.sub('(\s)'+actualToken+'$', ' '+actualEntity[index]+'.', orginalString)
	return orginalString

def processData(line):
	global orginalString
	sentenctAfterCleanUp = sentenceCleaning(line) #Sentence Cleanup
	tempsentences = tokenize.sent_tokenize(sentenctAfterCleanUp) #Break multiple sentences into tokens				
	orginalString = capitalizedFirtLetterOfSentence(tempsentences)
	sentences     = tokenize.sent_tokenize(orginalString) #Break multiple sentences into tokens

	data1 = [ngrams(sentence, n=1) for sentence in sentences] #create one gram
	data2 = [ngrams(sentence, n=2) for sentence in sentences] #create two grams
	data3 = [ngrams(sentence, n=3) for sentence in sentences] #create three grams
	#data4 = [ngrams(sentence, n=4) for sentence in sentences] #create three grams
	#data5 = [ngrams(sentence, n=5) for sentence in sentences] #create three grams
	#data6 = [ngrams(sentence, n=6) for sentence in sentences] #create three grams

	orginalString = matchData(data1, entityAfterCleanup, 1)
	orginalString = matchData(data2, entityAfterCleanup, 2)
	orginalString = matchData(data3, entityAfterCleanup, 3)
	#orginalString = matchData(data4, entityAfterCleanup, 4)
	#orginalString = matchData(data5, entityAfterCleanup, 5)
	#orginalString = matchData(data6, entityAfterCleanup, 6)

	orginalString = orginalString.replace('openbracket', '(')
	orginalString = orginalString.replace('closebracket', ')')
	return orginalString

global orginalString
actualEntity  = []
entityAfterCleanup = []

#sentence   = json.loads(sys.argv[1])
cachedData = json.loads(sys.argv[2])
sentence   =  sys.argv[1].replace("\n", "newline")

for i in cachedData['exam']:
        actualEntity.append(i['oldName'])
        entityAfterCleanup.append(i['newName'])

for j in cachedData['basecourse']:
        actualEntity.append(j['oldName'])
        entityAfterCleanup.append(j['newName'])

#for z in cachedData['institute']:
#       actualEntity.append(z['oldName'])
#        entityAfterCleanup.append(z['newName'])

p1 = processData(sentence)
p2 = p1#.replace("\\", "")
p3 = p2.replace("newline", "<br>")
print p3
