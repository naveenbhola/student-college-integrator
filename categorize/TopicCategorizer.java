import java.io.*; 
import java.util.*; 
public class TopicCategorizer 
{ 
  
  HashMap origMap = new HashMap();
  

  Porter p = new Porter();
  public TopicCategorizer(String wtFile) {
	  try {
		String dataLine;
		  FileInputStream fin;
		  fin = new FileInputStream (wtFile);
		  while(true) {
			  dataLine = (new DataInputStream((InputStream)fin)).readLine();
			  if(dataLine==null) {
				  break;
			  }

			  dataLine = dataLine.toLowerCase();
			  String[] arrData = dataLine.split("\\s+");
			
			  String word = arrData[0];
			  word = word.replaceAll("[^a-z]"," ");
			  String file = arrData[1];
			  if(origMap.containsKey(word)) {
				  HashMap tempMap = (HashMap)origMap.get(word);
				  if(tempMap.containsKey(file)) {
					  System.out.println("EROOOOOOROOOOOOOOOOOOOOOOERRRRRRRRRRRRR");
				  }else {
					  tempMap.put(file,arrData[2]);
				  }
				  origMap.put(word,tempMap);
			  }else {
				  HashMap tempMap = new HashMap();
				  tempMap.put(file,arrData[2]);
				  origMap.put(word,tempMap);
			  }
		  }
	  }catch(Exception ex) {
		  ex.printStackTrace();
	  }
		//System.out.println("DATA = "+origMap.toString());
  }

			
			
  public void doWork(String dataLine, Vector retVect) {
        dataLine = dataLine.toLowerCase();
			  dataLine = dataLine.replaceAll("[^a-z]"," ");
			  String[] arrData = dataLine.split("\\s+");
			HashMap dataMap = new HashMap();
			for(int i = 0 ; i < arrData.length;i++) {	
                if(arrData[i].trim().equals("")) {
                    continue;
                }
                //System.out.println("arrData = "+arrData[i]);
				arrData[i] = p.stripSuffixes(arrData[i]);
				if(origMap.containsKey(arrData[i])) {
					HashMap tempMap = (HashMap)origMap.get(arrData[i]);
//                    System.out.println(arrData[i]+"     "+tempMap.toString());
					Iterator iter = tempMap.keySet().iterator();
					while(iter.hasNext()) {
						String fileName = (String)iter.next();
						if(dataMap.containsKey(fileName)) {
							String val = (String)tempMap.get(fileName);
							float tempVal = (new Float(val)).floatValue();
							tempVal += (new Float((String)dataMap.get(fileName))).floatValue();
							val = (new Float(tempVal)).toString();
							dataMap.put(fileName,val);
						}else {
							
							dataMap.put(fileName,(String)tempMap.get(fileName));
						}
					}
				}else {
					//System.out.println("DUMPED NOT FOUND = "+arrData[i]);
				}
			}
			float max = 0;
			String outFile = "";
			Iterator iter = dataMap.keySet().iterator();
			while(iter.hasNext()) {               
				String fileName = (String)iter.next();
				String val = (String)dataMap.get(fileName);
				if(Float.parseFloat(val) > max) {
					max = Float.parseFloat(val);
					outFile = fileName;
				}
			}

			iter = dataMap.keySet().iterator();
			while(iter.hasNext()) {               
				String fileName = (String)iter.next();
				String val = (String)dataMap.get(fileName);
				if(Float.parseFloat(val) > .90*max) {
					outFile = fileName;
                    retVect.add(outFile);
                    retVect.add(val);
                }
			}

//            System.out.println(dataMap.toString());
            

//			System.out.println("course = "+dataLine+" winner = "+outFile+" wt = "+max);
  }

			
		




  public static void main(String args[]) 
  { 
      TopicCategorizer mp = new TopicCategorizer(args[0]); //wts file 
      try
      {
          Vector retVect = new Vector();
          mp.doWork(args[1],retVect);
          for(int i =0; i < retVect.size(); i=i+2){
              System.out.println(retVect.get(i)+",");
          }
      }
      catch (Exception ex) {	
          ex.printStackTrace();
      }
  }
}
 
