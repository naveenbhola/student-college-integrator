import java.io.*; 
import java.util.*; 
import java.util.regex.*; 
/*
 *Initial Level duration cleanser for cleaning & normalizing the humanly fed durations & crawled data durations
 * to a standard , indexable (clustering) field (known as intermediate duration)
 *
 * Though some fixes need to be done in this code , but presently sufficing all the given scenarios
 * This method also needs to be called while adding the course duration from front-end (so as to extract intermediate duration)
 *
 */

public class DurationExtractor 
{ 
  
  HashMap origMap = new HashMap();
  HashMap synMap = new HashMap();
  int flag = 0;  
  Map<String,String> durationKeywords = new HashMap<String,String>();
  public DurationExtractor() {
      durationKeywords.put("year","(^\\d+$)|(^DOT\\d+$)|(^\\d*DOT\\d+$)|(^\\d*(DOT)?\\d+(HYPHEN|BY)\\d*(DOT)?\\d+$)");
      durationKeywords.put("month","(^\\d+$)|(^DOT\\d+$)|(^\\d*DOT\\d+$)|(^\\d*(DOT)?\\d+(HYPHEN|BY)\\d*(DOT)?\\d+$)");
      durationKeywords.put("day","(^\\d+$)|(^DOT\\d+$)|(^\\d*DOT\\d+$)|(^\\d*(DOT)?\\d+(HYPHEN|BY)\\d*(DOT)?\\d+$)");
      durationKeywords.put("week","(^\\d+$)|(^DOT\\d+$)|(^\\d*DOT\\d+$)|(^\\d*(DOT)?\\d+(HYPHEN|BY)\\d*(DOT)?\\d+$)");
      durationKeywords.put("semester","(^\\d+$)|(^DOT\\d+$)|(^\\d*DOT\\d+$)|(^\\d*(DOT)?\\d+(HYPHEN|BY)\\d*(DOT)?\\d+$)");
      durationKeywords.put("hour","(^\\d+$)|(^DOT\\d+$)|(^\\d*DOT\\d+$)|(^\\d+HYPHEN\\d+$)");
//      durationKeywords.put("time","(full)|(part)");
//
//      Synonyms map
      synMap.put("one","1");
      synMap.put("two","2");
      synMap.put("three","3");
      synMap.put("four","4");
      synMap.put("five","5");
      synMap.put("six","6");
      synMap.put("seven","7");
      synMap.put("eight","8");
      synMap.put("nine","9");
      synMap.put("ten","10");
      synMap.put("eleven","11");
      synMap.put("tweleve","12");
      synMap.put("thirteen","13");
      synMap.put("fourteen","14");
      synMap.put("fifteen","15");
      synMap.put("sixteen","16");
      synMap.put("seventeen","17");
      synMap.put("eighteen","18");
      synMap.put("nineteen","19");
      synMap.put("twenty","20");
      synMap.put("yrs","year");
      synMap.put("years","year");
      synMap.put("months","month");
      synMap.put("hours","hour");
      synMap.put("hrs","hour");
      synMap.put("days","day");
      synMap.put("weeks","week");
      synMap.put("semesters","semester");

  }

  public Vector parseQuery(String query)
  {
      Vector pQuery=new Vector();
      try
      {
          query = query.toLowerCase();
          Iterator iter = synMap.keySet().iterator();
          while(iter.hasNext()) {
              String key = (String)iter.next();
              String val = (String)synMap.get(key);
              query = query.replaceAll(key,val);
          }
//          System.out.println(query);
          query = query.replaceAll("((\\d*)\\/(\\d*))","$2BY$3");
          query = query.replaceAll("((\\d*)\\.(\\d+))","$2DOT$3");
          query = query.replaceAll("\\."," ");
          query = query.replaceAll("((\\d+)\\s*to\\s*(\\d+))","$2-$3");
          query = query.replaceAll("((\\d+)\\-(\\d+))","$2HYPHEN$3");
          query = query.replaceAll("[^A-Za-z0-9\\.\\-\\?\\/]"," ");
          String newq="";
          int index=-1;
          String duration="";
          String typeQuery="";
          //String[] queryStrings=query.split("[\\p{Punct}\\p{Blank}]");
          String[] queryStrings=query.split("[\\p{Punct}\\p{Blank}]");
search:
          for(int i=0;i<queryStrings.length;i++)
          {
              if(durationKeywords.get(queryStrings[i])!=null)
              {
                  index=i;
                  break search;
              }

          }

          if((index>=1)&&(index<queryStrings.length))
          {
              int durationCount=index-1;
              while((durationCount>0)&&Pattern.matches("^[\\p{Punct}\\p{Blank})]*$", queryStrings[durationCount]))
              {
                  durationCount--;
              }
//              System.out.println("durationCount++"+durationCount);
              boolean isDuration = Pattern.matches(durationKeywords.get(queryStrings[index]), queryStrings[durationCount]);
//              System.out.println("isDuration++"+isDuration);
//              System.out.println("isDuration++"+queryStrings[index]);
//              System.out.println("isDuration++"+queryStrings[durationCount]);
              boolean isDurationNotPer = false;

              if(index<queryStrings.length-1){
                  isDurationNotPer = Pattern.matches("per",queryStrings[index+1]);
              }
              if(isDuration && !isDurationNotPer)
              {
                  if(queryStrings[durationCount].contains("BY")){

                      String[] durValue=queryStrings[durationCount].split("BY");

//                      System.out.println("num"+durValue[0]);
//                      System.out.println("den"+durValue[1]);
                      queryStrings[durationCount] = Float.toString((new Float(durValue[0].replaceAll("DOT",".")).floatValue())/(new Float(durValue[1].replaceAll("DOT",".")).floatValue()));
//                      System.out.println("den"+queryStrings[durationCount]);
                  }
                  
                  duration=queryStrings[durationCount] +" "+ queryStrings[index];

                  duration = duration.replaceAll("DOT",".");
                  duration = duration.replaceAll("HYPHEN","-");
                  duration = duration.replaceAll("BY","/");
                  flag = 1;
                  pQuery.addElement(duration.trim());
                  String[] stringSplit=query.split(queryStrings[index],2);
                  if(stringSplit.length >= 2){
//                      String[] stringSplit=query.split(queryStrings[index],2);
                        stringSplit[1] =stringSplit[1].replaceAll("DOT","."); 
                        stringSplit[1] =stringSplit[1].replaceAll("HYPHEN","-"); 
                        stringSplit[1] =stringSplit[1].replaceAll("BY","/"); 
                      Vector rightQuery=parseQuery(stringSplit[1]);
                      for(int i =0; i < rightQuery.size(); i++){
                          pQuery.addElement(rightQuery.get(i));
//                          System.out.println("hi"+rightQuery.get(i));
                      }
                  }
//                  System.out.println("exDuration++"+duration);
/*                  String[] stringSplit=query.split(queryStrings[index],2);
                  String q1[]=newq.split(queryStrings[durationCount]);
                  Vector leftQuery=parseQuery(stringSplit[1]);
                  newq=q1[0]+leftQuery.elementAt(0);
                  duration=duration+","+leftQuery.elementAt(1);
                  typeQuery=typeQuery+" "+leftQuery.elementAt(2);
                  pQuery.addElement(rmDuration(newq).trim());
                  pQuery.addElement(duration.trim());
                  pQuery.addElement(typeQuery.trim());*/
/*                      for(int i =0; i < pQuery.size(); i=i+1){
                          System.out.println("TBR"+pQuery.get(i));
//                          System.out.println("hi"+rightQuery.get(i));
                      }*/

                  return pQuery;
              }
              else{
                  String[] stringSplit=query.split(queryStrings[index],2);
                  if(stringSplit.length >= 2){
//                      String[] stringSplit=query.split(queryStrings[index],2);
//                      System.out.println(stringSplit[0]);
//                      System.out.println(stringSplit[1]);
                      stringSplit[1] =stringSplit[1].replaceAll("DOT","."); 
                      stringSplit[1] =stringSplit[1].replaceAll("HYPHEN","-"); 
                      stringSplit[1] =stringSplit[1].replaceAll("BY","/"); 
//                      System.out.println(stringSplit[1]);

                      Vector rightQuery=parseQuery(stringSplit[1]);
//                      System.out.println(rightQuery.get(0));
//                      System.out.println(pQuery.size());
                      for(int i =0; i < rightQuery.size(); i++){
                          pQuery.addElement(rightQuery.get(i));
//                          System.out.println("hi"+rightQuery.get(i));
                      }
//                      System.out.println(pQuery.size());
                  }
              }
          }
/*          pQuery.addElement(rmDuration(newq).trim());
          pQuery.addElement(duration.trim());
          pQuery.addElement(typeQuery.trim());*/
          return pQuery;
      }
      catch (Exception ex) {
//          System.out.println("exDuration++");
          return pQuery;
      }
  }
			
 
  private String rmDuration(String query)
  {
      String newq="";
      String[] stringSplit=query.split("durat");
      for(int i=0;i<stringSplit.length;i++)
      {
          newq=newq+stringSplit[i];
      }
      return newq;
  }

  public static void main(String args[]) 
  { 
      DurationExtractor mp = new DurationExtractor(); //wts file 
      try
      {
          Vector retVect = new Vector();
          retVect = mp.parseQuery(args[0]);
          if(retVect.size() <= 0){
              System.out.println("");
          }
          else{
              String intermediateDurations;
              intermediateDurations = (String)retVect.get(0);
              for(int i =1; i < retVect.size(); i++){
                  intermediateDurations +=","+retVect.get(i);
              }
              System.out.println(intermediateDurations);
          }
      }
      catch (Exception ex) {	
          System.out.println("");
      }
  }
}
 
