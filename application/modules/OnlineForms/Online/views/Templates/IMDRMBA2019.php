<script>
function hidePersonalDiv(obj)
{
		document.getElementById(obj).style.display = 'none';
		document.getElementById(obj).classList.add('clearFields');
		document.getElementById('EquivalentPercentageIMDRMBA2019').removeAttribute('required');
		document.getElementById('MarksObtainedIMDRMBA2019').removeAttribute('required');
  		document.getElementById('TotalMarksIMDRMBA2019').removeAttribute('required');	
}

function showPersonalDiv()
{
	var obj = document.getElementById('MarkingSystemIMDRMBA20190').checked;
	if (obj){
		var elem = document.getElementById('MarkingSystem_Percent System')
		elem.style.display = 'block';
		elem.classList.remove('clearFields');
		document.getElementById('MarksObtainedIMDRMBA2019').setAttribute('required','true');
  		document.getElementById('TotalMarksIMDRMBA2019').setAttribute('required','true');
  		document.getElementById('EquivalentPercentageIMDRMBA2019').removeAttribute('required');	
	}
	else{
		var elem = document.getElementById('MarkingSystem_Pointer/Credit System')
		elem.style.display ='block';
		elem.classList.remove('clearFields');
		document.getElementById('EquivalentPercentageIMDRMBA2019').setAttribute('required','true');
		document.getElementById('MarksObtainedIMDRMBA2019').removeAttribute('required');
  		document.getElementById('TotalMarksIMDRMBA2019').removeAttribute('required');	
	}

}



</script>

<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>
			<li>
					<h3 class="upperCase">Personal Information</h3>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Upload Your Signature here<span>*</span>: </label>
				<div class='fieldBoxLarge'>
				<input type='file' name='userApplicationfile[]' id='UploadYourSignatureIMDRMBA2019'  required="true"   />
				<input type='hidden' name='UploadYourSignatureIMDRMBA2019Valid' value=''>
				<span class="imageSizeInfo">Maximum Upload File Size 2 MB</span>
				<div style='display:none'><div class='errorMsg' id= 'UploadYourSignatureIMDRMBA2019_error'></div></div>
				<label id="ssnSignature" style="width: 370px;">
								<?php if(isset($UploadYourSignatureIMDRMBA2019) && $UploadYourSignatureIMDRMBA2019!=""){ ?>
						  			<script>
						      		document.getElementById("ssnSignature").innerHTML = "<span style= 'color:green;'>File Uploaded.</span><span style='font-size:12px;color:grey'>Upload again if you want to replace existing file.</span>";
						  			</script>
								<?php } ?>
							</label>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Caste<span>*</span>: </label>
				<div class='fieldBoxLarge'>
				<select name='CasteIMDRMBA2019' id='CasteIMDRMBA2019'      validate="validateSelect"   required="true"   caption="Caste"  ><option value='' >Select</option><option value='N.A.' >- </option><option value=' Gavali Dhangar' > Gavali Dhangar</option><option value=' Hindu' > Hindu</option><option value=' Hindu Brahmin' > Hindu Brahmin</option><option value=' Hindu Maratha' > Hindu Maratha</option><option value=' Jai chaturtha' > Jai chaturtha</option><option value=' Jain ' > Jain </option><option value=' Jain chaturtha' > Jain chaturtha</option><option value=' Koli' > Koli</option><option value=' Mahadeo Koli' > Mahadeo Koli</option><option value=' Mahadev Koli' > Mahadev Koli</option><option value='Aarwa Mala' >Aarwa Mala</option><option value='ADIVASI' >ADIVASI</option><option value='Adivasi Varli' >Adivasi Varli</option><option value='afghani ' >afghani </option><option value='Agari ' >Agari </option><option value='AGARI' >AGARI</option><option value='Agarwal' >Agarwal</option><option value='Agrawal ' >Agrawal </option><option value='Agri' >Agri</option><option value='AHIR' >AHIR</option><option value='Andh ' >Andh </option><option value='Andh' >Andh</option><option value='Arya Vaishya' >Arya Vaishya</option><option value='ATTAR- 202 OBC' > ATTAR- 202 OBC</option><option value='Avadh Vanshia' >Avadh Vanshia</option><option value='AWADHIYA' >AWADHIYA</option><option value='Bahayat' >Bahayat</option><option value='Bairagi' >Bairagi</option><option value='Balmiki' >Balmiki</option><option value='Baniya' >Baniya</option><option value='Banjara' >Banjara</option><option value='Banjara ' >Banjara </option><option value='BARAI' >BARAI</option><option value='Bari ' >Bari </option><option value='Barjubi' >Barjubi</option><option value='basor' >basor</option><option value='Bauddha' >Bauddha</option><option value='Beda Jangam' >Beda Jangam</option><option value='Beldar' >Beldar</option><option value='Beldar ' >Beldar </option><option value='BENGALI' >BENGALI</option><option value='BENGALI-SC' > BENGALI-SC</option><option value='Berad' >Berad</option><option value='Bhandari ' >Bhandari </option><option value='BHANDARI' >BHANDARI</option><option value='BHANGI' >BHANGI</option><option value='Bhangi ' >Bhangi </option><option value='Bharadi' >Bharadi</option><option value='Bhavsar' >Bhavsar</option><option value='Bhawsar' >Bhawsar</option><option value='Bhawsar ' >Bhawsar </option><option value='Bhil ' >Bhil </option><option value='BHILLA' >BHILLA</option><option value='Bhilla ' >Bhilla </option><option value='Bhoi' >Bhoi</option><option value='BHOVI' >BHOVI</option><option value='BHUJWA' >BHUJWA</option><option value='BHUMIHAR' >BHUMIHAR</option><option value='Bhuyan ' >Bhuyan </option><option value='BOHRI' >BOHRI</option><option value='bothra' >bothra</option><option value='Bouddha' >Bouddha</option><option value='Boudha ' >Boudha </option><option value='Brahman' >Brahman</option><option value='Brahmin' >Brahmin</option><option value='Brahmin' >Brahmin</option><option value='Brahmin' >Brahmin</option><option value='Open' >Open</option><option value='Buddha' >Buddha</option><option value='Buddhijam' >Buddhijam</option><option value='BUNTS' >BUNTS</option><option value='Burud' >Burud</option><option value='C K P' >C K P</option><option value='C.K.P.' >C.K.P.</option><option value='CANADE' >CANADE</option><option value='Catholic' >Catholic</option><option value='Chambhar ' >Chambhar </option><option value='Chambhar' >Chambhar</option><option value='Chambhar SC' >Chambhar SC</option><option value='chatri' >chatri</option><option value='CKP' >CKP</option><option value='CRISTIAN' >CRISTIAN</option><option value='Dagadi ' >Dagadi </option><option value='Dangi' >Dangi</option><option value='Dawoodi Bhora' >Dawoodi Bhora</option><option value='DEORI' >DEORI</option><option value='Dhanagar' >Dhanagar</option><option value='Dhangar' >Dhangar</option><option value='Dhangar ' >Dhangar </option><option value='Dhar' >Dhar</option><option value='DHIWAR' >DHIWAR</option><option value='Dhobi' >Dhobi</option><option value='Dhobi (Parit)' >Dhobi (Parit)</option><option value='DHODIA' >DHODIA</option><option value='Dhor ' >Dhor </option><option value='Dhor' >Dhor</option><option value='ediga' >ediga</option><option value='EZHAVA' >EZHAVA</option><option value='GADHWALI' >GADHWALI</option><option value='Gadihor' >Gadihor</option><option value='Gadilohar' >Gadilohar</option><option value='Gavali ' >Gavali </option><option value='Gavali' >Gavali</option><option value='Gavandi' >Gavandi</option><option value='GAVIT' >GAVIT</option><option value='General' >General</option><option value='Ghisadi' >Ghisadi</option><option value='Ghisadi (8)' >Ghisadi (8)</option><option value='GOLEWAR' >GOLEWAR</option><option value='Golla' >Golla</option><option value='Gond' >Gond</option><option value='GONDHALI' >GONDHALI</option><option value='Gopal' >Gopal</option><option value='Gor Banjara' >Gor Banjara</option><option value='Gosavi ' >Gosavi </option><option value='Gosavi' >Gosavi</option><option value='Gowari' >Gowari</option><option value='Gujar ' >Gujar </option><option value='Gujarathi' >Gujarathi</option><option value='Gujrathi' >Gujrathi</option><option value='Gujrati' >Gujrati</option><option value='GULATI' >GULATI</option><option value='GUNDLA' >GUNDLA</option><option value='GURAV' >GURAV</option><option value='Gurav ' >Gurav </option><option value='Halba' >Halba</option><option value='HALWAI' >HALWAI</option><option value='HANBAR' >HANBAR</option><option value='Hatkar ' >Hatkar </option><option value='HINDU' >HINDU</option><option value='HINDU BHANGI' >HINDU BHANGI</option><option value='Hindu Bhoi' >Hindu Bhoi</option><option value='Hindu Brahmin' >Hindu Brahmin</option><option value='Hindu Chambhar' >Hindu Chambhar</option><option value='HINDU DEVANJ Koshti' >HINDU DEVANJ Koshti</option><option value='Hindu dhangar' >Hindu dhangar</option><option value='Hindu Dorik' >Hindu Dorik</option><option value='HIndu Gavali' >HIndu Gavali</option><option value='Hindu Ghadi' >Hindu Ghadi</option><option value='Hindu Goar Banjara' >Hindu Goar Banjara</option><option value='Hindu Gondhali' >Hindu Gondhali</option><option value='HINDU GUJAR' >HINDU GUJAR</option><option value='Hindu Hatkar' >Hindu Hatkar</option><option value='Hindu Jain' >Hindu Jain</option><option value='Hindu Joshi' >Hindu Joshi</option><option value='Hindu Kaikadi' >Hindu Kaikadi</option><option value='HINDU KHANDELWAL' >HINDU KHANDELWAL</option><option value='Hindu Khatik' >Hindu Khatik</option><option value='Hindu Ko Brahmin' >Hindu Ko Brahmin</option><option value='Hindu Koshti' >Hindu Koshti</option><option value='HINDU KOSTHI' >HINDU KOSTHI</option><option value='Hindu Kshatriya Patkar' >Hindu Kshatriya Patkar</option><option value='Hindu Kshatriya(Patkar)' >Hindu Kshatriya(Patkar)</option><option value='Hindu Kumbhar' >Hindu Kumbhar</option><option value='Hindu Kunbi' >Hindu Kunbi</option><option value='Hindu Kurmudi Joshi' >Hindu Kurmudi Joshi</option><option value='Hindu Ladshakhiy Vani' >Hindu Ladshakhiy Vani</option><option value='Hindu leva patidar' >Hindu leva patidar</option><option value='Hindu Lonari' >Hindu Lonari</option><option value='Hindu Mahar' >Hindu Mahar</option><option value='Hindu Mahayavanshi' >Hindu Mahayavanshi</option><option value='Hindu Maheshwari' >Hindu Maheshwari</option><option value='Hindu Mali ' >Hindu Mali </option><option value='Hindu Maratha' >Hindu Maratha</option><option value='HINDU MUNERWARALU' >HINDU MUNERWARALU</option><option value='Hindu Namdeo Shimpi' >Hindu Namdeo Shimpi</option><option value='Hindu Nathpanthi Gosavi' >Hindu Nathpanthi Gosavi</option><option value='Hindu Parit' >Hindu Parit</option><option value='Hindu Patkar' >Hindu Patkar</option><option value='Hindu Rajput' >Hindu Rajput</option><option value='HINDU SALI' >HINDU SALI</option><option value='Hindu Saliya' >Hindu Saliya</option><option value='Hindu Shegar Dhangar' >Hindu Shegar Dhangar</option><option value='Hindu Shimpi ' >Hindu Shimpi </option><option value='HINDU SONKOLI' >HINDU SONKOLI</option><option value='Hindu Teli' >Hindu Teli</option><option value='Hindu Vadar' >Hindu Vadar</option><option value='Hindu Vaity' >Hindu Vaity</option><option value='Hindu Vani' >Hindu Vani</option><option value='Hindu Vanjari' >Hindu Vanjari</option><option value='Hindu Vayshywani' >Hindu Vayshywani</option><option value='Hindu Wanjari' >Hindu Wanjari</option><option value='Hindu-aratha' > Hindu-aratha</option><option value='Hindu-Brahmin' >Hindu-Brahmin</option><option value='Hindu-Dhangar' >Hindu-Dhangar</option><option value='Hindu-Kasar' >Hindu-Kasar 87</option><option value='Hindu-Khatik' >Hindu-Khatik</option><option value='Hindu-Kumbhar' >Hindu-Kumbhar</option><option value='Hindu-Kunbi' >Hindu-Kunbi</option><option value='Hindu-Mahar' >Hindu-Mahar</option><option value='Hindu-Mang' >Hindu-Mang</option><option value='Hindu-Maratha' >Hindu-Maratha</option><option value='Hindu-Padmashali' >Hindu-Padmashali</option><option value='Hindu-Patharvat' >Hindu-Patharvat</option><option value='Hindu-Vadar' >Hindu-Vadar</option><option value='Hindu-Vaishya' >Hindu-Vaishya</option><option value='Hindu-Vanjari' >Hindu-Vanjari</option><option value='Holar' >Holar</option><option value='JAAT' >JAAT</option><option value='Jain ' >Jain </option><option value='Jain' >Jain</option><option value='Jain chatirtha' >Jain chatirtha</option><option value='Jain chaturtha' >Jain chaturtha</option><option value='JANGAM' >JANGAM</option><option value='Joshi' >Joshi</option><option value='Kadia ' >Kadia </option><option value='KADIYA' >KADIYA</option><option value='Kaibartta' >Kaibartta</option><option value='Kaikadi ' >Kaikadi </option><option value='Kalar' >Kalar</option><option value='Kamathi ' >Kamathi </option><option value='Kanjarbhat ' >Kanjarbhat </option><option value='Kanjurbhat' >Kanjurbhat</option><option value='Kankayya (Dhor)' >Kankayya (Dhor)</option><option value='Kanojiya' >Kanojiya</option><option value='Kasar' >Kasar</option><option value='Kasar  ' >Kasar  </option><option value='Kasar ' >Kasar </option><option value='Kasar-87' >Kasar-87</option><option value='KASHYAP' >KASHYAP</option><option value='Kathar Wani' >Kathar Wani</option><option value='Katipa-Mulla' >Katipa-Mulla</option><option value='KAWAR' >KAWAR</option><option value='KAYASTHA' >KAYASTHA</option><option value='KHARWA' >KHARWA</option><option value='Khatik' >Khatik</option><option value='KHOJA' >KHOJA</option><option value='kholhati' >kholhati</option><option value='KHUSHWAHA' >KHUSHWAHA</option><option value='Kokana' >Kokana</option><option value='Kokani' >Kokani</option><option value='kolhati ' >kolhati </option><option value='Koli' >Koli</option><option value='Koli ' >Koli </option><option value='KOMTI' >KOMTI</option><option value='kora' >kora</option><option value='Koraku' >Koraku</option><option value='Koravi' >Koravi</option><option value='Kori' >Kori</option><option value='Koshti' >Koshti</option><option value='KOSTHI' >KOSTHI</option><option value='KSHATRIYA' >KSHATRIYA</option><option value='KUBERA' >KUBERA</option><option value='Kumavat' >Kumavat</option><option value='Kumbhar' >Kumbhar</option><option value='Kumbhar ' >Kumbhar </option><option value='Kunabi' >Kunabi</option><option value='Kunbi' >Kunbi</option><option value='Kunbi ' >Kunbi </option><option value='Kunbi Maratha' >Kunbi Maratha</option><option value='Kunbi Patil' >Kunbi Patil</option><option value='KURMI' >KURMI</option><option value='Kutchi' >Kutchi</option><option value='Lad Shakhiya wani' >Lad Shakhiya wani</option><option value='Ladshakhiya Wani' >Ladshakhiya Wani</option><option value='Ladshyakhiy Vani' >Ladshyakhiy Vani</option><option value='Ladwani ' >Ladwani </option><option value='Lakhera' >Lakhera</option><option value='laman' >laman</option><option value='Leva Patidar ' >Leva Patidar </option><option value='leva patidar' >leva patidar</option><option value='Leva Patidar 83' >Leva Patidar 83</option><option value='Leva Patil ' >Leva Patil </option><option value='LEVE GUJAR' >LEVE GUJAR</option><option value='Lewa Gujar ' >Lewa Gujar </option><option value='Lewa Patidar' >Lewa Patidar</option><option value='Ligayat' >Ligayat</option><option value='Ligayat Vani' >Ligayat Vani</option><option value='Lingayat' >Lingayat</option><option value='Lingayat ' >Lingayat </option><option value='Lingayat Mali' >Lingayat Mali</option><option value='Lingayat Wani' >Lingayat Wani</option><option value='Lohana Halay' >Lohana Halay</option><option value='Lohar' >Lohar</option><option value='Lonar' >Lonar</option><option value='Lonari' >Lonari</option><option value='Machhimar' >Machhimar</option><option value='MADGI' >MADGI</option><option value='Maghawal' >Maghawal</option><option value='Mahadeo Koli  ' >Mahadeo Koli  </option><option value='Mahadeo Koli' >Mahadeo Koli</option><option value='Mahadev Koli' >Mahadev Koli</option><option value='Mahar' >Mahar</option><option value='Mahar ' >Mahar </option><option value='Maheshwari' >Maheshwari</option><option value='mala' >mala</option><option value='Mala (Sc)' >Mala (Sc)</option><option value='Mala.' >Mala.</option><option value='Mali ' >Mali </option><option value='Mang' >Mang</option><option value='Mang (Madar)' >Mang (Madar)</option><option value='Mannervarlu' >Mannervarlu</option><option value='Maratha' >Maratha</option><option value='Maratha ' >Maratha </option><option value='Maratha Kunbi' >Maratha Kunbi</option><option value='Marthoma' >Marthoma</option><option value='Marwadi' >Marwadi</option><option value='Marwadi Vaishnav' >Marwadi Vaishnav</option><option value='Marwari ' >Marwari </option><option value='Matang' >Matang</option><option value='Matang ' >Matang </option><option value='Mauchi' >Mauchi</option><option value='MEHARA' >MEHARA</option><option value='Mehetar ' >Mehetar </option><option value='Mehtar' >Mehtar</option><option value='Mistry' >Mistry</option><option value='MODHGHACHI' >MODHGHACHI</option><option value='Mudliyar' >Mudliyar</option><option value='Munnerwarlu' >Munnerwarlu</option><option value='Musalman ' >Musalman </option><option value='Muslim ' >Muslim </option><option value='MUSLIM' >MUSLIM</option><option value='Muslim-Pinjari ' >Muslim-Pinjari</option><option value='MUSLIM-ATTAR' >MUSLIM-ATTAR</option><option value='Muslim-Julah' >Muslim-Julah</option><option value='N.Shimpi' >N.Shimpi</option><option value='NA' >NA</option><option value='NA Banjara VJA' >NA Banjara VJA</option><option value='Nabhik' >Nabhik</option><option value='Nabhik ' >Nabhik </option><option value='Nair' >Nair</option><option value='Namdeo Shimpi' >Namdeo Shimpi</option><option value='Namdev Shimpi' >Namdev Shimpi</option><option value='Nath panti Davri gosavi' >Nath panti Davri gosavi</option><option value='Nav Bouddha' >Nav Bouddha</option><option value='Nav Bouddha' >Nav Bouddha</option><option value='Nav Boudha ' >Nav Boudha </option><option value='Nav Boudha' >Nav Boudha</option><option value='Nav Boudhha' >Nav Boudhha</option><option value='Nav Buddha' >Nav Buddha</option><option value='Nav-Boudhha' >Nav-Boudhha</option><option value='Navbuddha' >Navbuddha</option><option value='Neo-Buddha' >Neo-Buddha</option><option value='Nhavi ' >Nhavi </option><option value='Nhavi' >Nhavi</option><option value='O B C' >O B C</option><option value='O.B.C' >O.B.C</option><option value='OBC' >OBC</option><option value='ojha' >ojha</option><option value='Oswal ' >Oswal </option><option value='OTHER' >OTHER</option><option value='PADAMSHALI' >PADAMSHALI</option><option value='Padmasali' >Padmasali</option><option value='Padmashali' >Padmashali</option><option value='Panchal ' >Panchal </option><option value='Panchal' >Panchal</option><option value='Pancham' >Pancham</option><option value='Paradhi' >Paradhi</option><option value='paradhi ' >paradhi </option><option value='Pardhan ' >Pardhan </option><option value='Pareet' >Pareet</option><option value='Parit ' >Parit </option><option value='Parit' >Parit</option><option value='PARSI' >PARSI</option><option value='Pasi' >Pasi</option><option value='patel' >patel</option><option value='Patkar ' >Patkar </option><option value='Patkar' >Patkar</option><option value='Pawara' >Pawara</option><option value='Pawara S.T.' >Pawara S.T.</option><option value='Pillai' >Pillai</option><option value='Pinjari ' >Pinjari </option><option value='Pradhan' >Pradhan</option><option value='Pradhan ' >Pradhan </option><option value='Prajapati' >Prajapati</option><option value='PROTESTANTS' >PROTESTANTS</option><option value='PUNJABI' >PUNJABI</option><option value='RAJASTHANI' >RAJASTHANI</option><option value='RAJGOND' >RAJGOND</option><option value='Rajput ' >Rajput </option><option value='Rajput' >Rajput</option><option value='Rajput Bhamata' >Rajput Bhamata</option><option value='Rajput Bhamta' >Rajput Bhamta</option><option value='Ramoshi' >Ramoshi</option><option value='rangari' >rangari</option><option value='Rawal' >Rawal</option><option value='Reva Gujar' >Reva Gujar</option><option value='Roman Catholic' >Roman Catholic</option><option value='S C' >S C</option><option value='S T' >S T</option><option value='S T-' >S T-</option><option value='S.C' >S.C</option><option value='S.T.' >S.T.</option><option value='SAINI' >SAINI</option><option value='Saitwal' >Saitwal</option><option value='Sali' >Sali</option><option value='Sali ' >Sali </option><option value='Sanagar' >Sanagar</option><option value='Sangar' >Sangar</option><option value='SAO TELI' >SAO TELI</option><option value='Sarasvat Brahman' >Sarasvat Brahman</option><option value='SC' >SC</option><option value='SCHEDULED CASTE' >SCHEDULED CASTE</option><option value='Shegar' >Shegar</option><option value='Shelar' >Shelar</option><option value='shik' >shik</option><option value='Shikalgar' >Shikalgar</option><option value='Shimpi ' >Shimpi </option><option value='Shimpi' >Shimpi</option><option value='Shimpi (Namdev)' >Shimpi (Namdev)</option><option value='Sidhi' >Sidhi</option><option value='SIKH' >SIKH</option><option value='SINDHI' >SINDHI</option><option value='Somvanshi Arya' >Somvanshi Arya</option><option value='Somvanshiya' >Somvanshiya</option><option value='Sonar ' >Sonar </option><option value='Sonar' >Sonar</option><option value='Sutar' >Sutar</option><option value='Sutar ' >Sutar </option><option value='Swakul Sali' >Swakul Sali</option><option value='Swakul Sali ' >Swakul Sali </option><option value='Syrian' >Syrian</option><option value='Tailor' >Tailor</option><option value='Takari' >Takari</option><option value='Tambat' >Tambat</option><option value='Tamboli' >Tamboli</option><option value='tanti' >tanti</option><option value='Telgu Fulmali' >Telgu Fulmali</option><option value='Telgu Phulmali' >Telgu Phulmali</option><option value='Teli ' >Teli </option><option value='Teli' >Teli</option><option value='Thai Ahom' >Thai Ahom</option><option value='Thakar' >Thakar</option><option value='Thakur' >Thakur</option><option value='Tilori ' >Tilori </option><option value='Tvashta Kasar' >Tvashta Kasar</option><option value='Twashta Kasar' >Twashta Kasar</option><option value='Udpi' >Udpi</option><option value='Vadar' >Vadar</option><option value='Vaddar' >Vaddar</option><option value='Vaddari' >Vaddari</option><option value='Vaidu' >Vaidu</option><option value='Vaishnav ' >Vaishnav </option><option value='VAISHYA' >VAISHYA</option><option value='Vaishyawani ' >Vaishyawani </option><option value='Vaishyawani' >Vaishyawani</option><option value='VALMIKI' >VALMIKI</option><option value='Vanjari ' >Vanjari </option><option value='Vanjari' >Vanjari</option><option value='varli' >varli</option><option value='Veershaiva Jangam' >Veershaiva Jangam</option><option value='Velutedan' >Velutedan</option><option value='Vishva Brahmin' >Vishva Brahmin</option><option value='Wadar ' >Wadar </option><option value='Wani  ' >Wani  </option><option value='Wani' >Wani</option><option value='Wani ' >Wani </option><option value='WANJARI' >WANJARI</option><option value='YADAV' >YADAV</option><option value='Yalmalvan' >Yalmalvan</option><option value='Yelam' >Yelam</option><option value='Yellam' >Yellam</option><option value='Zingabhoi' >Zingabhoi</option></select>
				<?php if(isset($CasteIMDRMBA2019) && $CasteIMDRMBA2019!=""){ ?>
			      <script>
				  var selObj = document.getElementById("CasteIMDRMBA2019"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $CasteIMDRMBA2019;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'CasteIMDRMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Sub-caste: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SubcasteIMDRMBA2019' id='SubcasteIMDRMBA2019'  validate="validateStr"    caption="Sub Caste"   minlength="1"   maxlength="50"      value=''   />
				<?php if(isset($SubcasteIMDRMBA2019) && $SubcasteIMDRMBA2019!=""){ ?>
				  <script>
				      document.getElementById("SubcasteIMDRMBA2019").value = "<?php echo str_replace("\n", '\n', $SubcasteIMDRMBA2019 );  ?>";
				      document.getElementById("SubcasteIMDRMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SubcasteIMDRMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Other known Languages: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='OtherknownLanguagesIMDRMBA2019' id='OtherknownLanguagesIMDRMBA2019'  validate="validateStr"    caption="Languages"   minlength="1"   maxlength="100"      value=''   />
				<?php if(isset($OtherknownLanguagesIMDRMBA2019) && $OtherknownLanguagesIMDRMBA2019!=""){ ?>
				  <script>
				      document.getElementById("OtherknownLanguagesIMDRMBA2019").value = "<?php echo str_replace("\n", '\n', $OtherknownLanguagesIMDRMBA2019 );  ?>";
				      document.getElementById("OtherknownLanguagesIMDRMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'OtherknownLanguagesIMDRMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Aadhaar Card<span>*</span>: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='AadhaarCardIMDRMBA2019' id='AadhaarCardIMDRMBA2019'  validate="validateInteger"   required="true"   caption="Aadhaar Card number"   minlength="1"   maxlength="12"      value=''   />
				<?php if(isset($AadhaarCardIMDRMBA2019) && $AadhaarCardIMDRMBA2019!=""){ ?>
				  <script>
				      document.getElementById("AadhaarCardIMDRMBA2019").value = "<?php echo str_replace("\n", '\n', $AadhaarCardIMDRMBA2019 );  ?>";
				      document.getElementById("AadhaarCardIMDRMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'AadhaarCardIMDRMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Admission Quota Type<span>*</span>: </label>
				<div class='fieldBoxLarge'>
				<select name='AdmissionQuotaTypeIMDRMBA2019' id='AdmissionQuotaTypeIMDRMBA2019'      validate="validateSelect"   required="true"   caption="Admission Quota Type"  ><option value='Institutional' selected>Institutional</option></select>
				<?php if(isset($AdmissionQuotaTypeIMDRMBA2019) && $AdmissionQuotaTypeIMDRMBA2019!=""){ ?>
			      <script>
				  var selObj = document.getElementById("AdmissionQuotaTypeIMDRMBA2019"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $AdmissionQuotaTypeIMDRMBA2019;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'AdmissionQuotaTypeIMDRMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Reserve Category<span>*</span>: </label>
				<div class='fieldBoxLarge'>
				<select name='ReserveCategoryIMDRMBA2019' id='ReserveCategoryIMDRMBA2019'      validate="validateSelect"   required="true"   caption="Reserve Category"  ><option value='' >Select</option><option value='OPEN' >OPEN</option><option value='OBC' >OBC</option><option value='ST' >ST</option><option value='SC'>SC</option><option value=' VJ/DT-A' >VJ/DT-A</option><option value=' NT-B' >NT-B</option><option value='NT-C' >NT-C</option><option value='SBC' >SBC</option></select>
				<?php if(isset($ReserveCategoryIMDRMBA2019) && $ReserveCategoryIMDRMBA2019!=""){ ?>
			      <script>
				  var selObj = document.getElementById("ReserveCategoryIMDRMBA2019"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $ReserveCategoryIMDRMBA2019;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ReserveCategoryIMDRMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>How do you came to know IMDR?<span>*</span>: </label>
				<div class='fieldBoxLarge' style="width: 220px">
				<input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="this"   name='HowknowIMDRMBA2019[]' id='HowknowIMDRMBA20190'   value='Shiksha.com'  checked  ></input><span >Shiksha.com</span>&nbsp;&nbsp;<br>
				<input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="this"   name='HowknowIMDRMBA2019[]' id='HowknowIMDRMBA20191'   value='Magazine/Newspaper'    ></input><span > Magazine/Newspaper</span>&nbsp;&nbsp;<br>
				<input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="this"   name='HowknowIMDRMBA2019[]' id='HowknowIMDRMBA20192'   value='Online banner/ Digital Media'></input><span style="width: 100%"> Online banner/ Digital Media</span>&nbsp;&nbsp;<br>
				<input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="this"   name='HowknowIMDRMBA2019[]' id='HowknowIMDRMBA20193'   value='IMDR Web site'    ></input><span > IMDR Web site</span>&nbsp;&nbsp;<br>
				<input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="this"   name='HowknowIMDRMBA2019[]' id='HowknowIMDRMBA20194'   value='Friends/Relatives'    ></input><span > Friends/Relatives</span>&nbsp;&nbsp;<br>
				<input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="this"   name='HowknowIMDRMBA2019[]' id='HowknowIMDRMBA20195'   value='Seniors/Friends at IMDR'    ></input><span > Seniors/Friends at IMDR </span>&nbsp;&nbsp;<br>
				<input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="this"   name='HowknowIMDRMBA2019[]' id='HowknowIMDRMBA20196'   value='Other'    ></input><span > Other</span>&nbsp;&nbsp;<br>
				<?php if(isset($HowknowIMDRMBA2019) && $HowknowIMDRMBA2019!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["HowknowIMDRMBA2019[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$HowknowIMDRMBA2019);
						    for($x=0;$x<count($arr);$x++){ ?>
						    	console.log("<?php echo $arr[$x];?>");
							  if(objCheckBoxes[i].value == "<?php echo $arr[$x];?>") {
								  objCheckBoxes[i].checked = true;
							  }
					      <?php
						    }
					      ?>
				    }
				</script>
			      <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'HowknowIMDRMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3>Graduation Details</h3>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Stream<span>*</span>: </label>
				<div class='fieldBoxLarge'>
				<select name='StreamIMDRMBA2019' id='StreamIMDRMBA2019'      validate="validateSelect"   required="true"   caption="Stream"  ><option value='' selected>Select</option><option value=' Allied Health science' > Allied Health science</option><option value=' Arts' > Arts</option><option value=' Ayurveda' > Ayurveda</option><option value=' Commerce' > Commerce</option><option value=' Education' > Education</option><option value=' Engineering' > Engineering</option><option value=' Home Science' > Home Science</option><option value=' Human Resource Management' > Human Resource Management</option><option value=' Industrial Training' > Industrial Training</option><option value=' Law' > Law</option><option value=' Management' > Management</option><option value=' Medicine' > Medicine</option><option value=' School Education' > School Education</option><option value=' Science' > Science</option><option value=' Other' > Other</option></select>
				<?php if(isset($StreamIMDRMBA2019) && $StreamIMDRMBA2019!=""){ ?>
			      <script>
				  var selObj = document.getElementById("StreamIMDRMBA2019"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $StreamIMDRMBA2019;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'StreamIMDRMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Qualifying Status<span>*</span>: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'   required="true"  divname = 'degree' name='QualifyingStatusIMDRMBA2019' id='QualifyingStatusIMDRMBA20190'   value='Completed'  checked  onclick="hideDiv(this,'Pursuing'); showPersonalDiv()"></input><span >Completed</span>&nbsp;&nbsp;
				<input type='radio'   required="true"  divname = 'degree' name='QualifyingStatusIMDRMBA2019' id='QualifyingStatusIMDRMBA20191'   value=' Pursuing'   onclick="hideDiv(this,'Completed');hidePersonalDiv('MarkingSystem_Percent System');hidePersonalDiv('MarkingSystem_Pointer/Credit System') " ></input><span > Pursuing</span>&nbsp;&nbsp;
				<?php if(isset($QualifyingStatusIMDRMBA2019) && $QualifyingStatusIMDRMBA2019!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["QualifyingStatusIMDRMBA2019"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $QualifyingStatusIMDRMBA2019;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'QualifyingStatusIMDRMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Admission Year<span>*</span>: </label>
				<div class='fieldBoxLarge'>
				<select name='AdmissionYearIMDRMBA2019' id='AdmissionYearIMDRMBA2019'      validate="validateSelect"   required="true"   caption="Admission year of your graduation"  ><option value='' selected>Select</option><option value=' 2019' > 2019</option><option value=' 2018' > 2018</option><option value=' 2017' > 2017</option><option value=' 2016' > 2016</option><option value=' 2015' > 2015</option><option value=' 2014' > 2014</option><option value=' 2013' > 2013</option><option value=' 2012' > 2012</option><option value=' 2011' > 2011</option><option value=' 2010' > 2010</option><option value=' 2009' > 2009</option><option value=' 2008' > 2008</option><option value=' 2007' > 2007</option><option value=' 2006' > 2006</option><option value=' 2005' > 2005</option><option value=' 2004' > 2004</option><option value=' 2003' > 2003</option><option value=' 2002' > 2002</option><option value=' 2001' > 2001</option><option value=' 2000' > 2000</option><option value=' 1999' > 1999</option><option value=' 1998' > 1998</option><option value=' 1997' > 1997</option><option value=' 1996' > 1996</option><option value=' 1995' > 1995</option><option value=' 1994' > 1994</option><option value=' 1993' > 1993</option><option value=' 1992' > 1992</option><option value=' 1991' > 1991</option><option value=' 1990' > 1990</option><option value=' 1989' > 1989</option><option value=' 1988' > 1988</option><option value=' 1987' > 1987</option><option value=' 1986' > 1986</option><option value=' 1985' > 1985</option><option value=' 1984' > 1984</option><option value=' 1983' > 1983</option><option value=' 1982' > 1982</option><option value=' 1981' > 1981</option><option value=' 1980' > 1980</option></select>
				<?php if(isset($AdmissionYearIMDRMBA2019) && $AdmissionYearIMDRMBA2019!=""){ ?>
			      <script>
				  var selObj = document.getElementById("AdmissionYearIMDRMBA2019"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $AdmissionYearIMDRMBA2019;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'AdmissionYearIMDRMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>PRN No./ Enrollment No.<span>*</span>: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PRN_EnrollmentIMDRMBA2019' id='PRN_EnrollmentIMDRMBA2019'  validate="validateStr"   required="true"   caption="PRN Number/Enrollment Number"   minlength="1"   maxlength="50"     tip="As per University Marksheet"   value=''   />
				<?php if(isset($PRN_EnrollmentIMDRMBA2019) && $PRN_EnrollmentIMDRMBA2019!=""){ ?>
				  <script>
				      document.getElementById("PRN_EnrollmentIMDRMBA2019").value = "<?php echo str_replace("\n", '\n', $PRN_EnrollmentIMDRMBA2019 );  ?>";
				      document.getElementById("PRN_EnrollmentIMDRMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PRN_EnrollmentIMDRMBA2019_error'></div></div>
				</div>
				</div>
			</li>

		<div id = 'degree_Completed'  style="display: none; ">
			<li >
				<div class='additionalInfoLeftCol' style="width: 735px;">
				<label>Marking System<span>*</span>: </label>
				<div class='fieldBoxLarge' style="width: 330px;">
				<input type='radio'      name='MarkingSystemIMDRMBA2019' id='MarkingSystemIMDRMBA20190'   value='Percent System'  checked divName = 'MarkingSystem' onclick="hideDiv(this,'Pointer/Credit System');showPersonalDiv();" onmouseover="showTipOnline('As per University Marksheet',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('As per University Marksheet',this);" onmouseout="hidetip();" >Percent System</span>&nbsp;&nbsp;
				<input type='radio'   divName = 'MarkingSystem'  name='MarkingSystemIMDRMBA2019' id='MarkingSystemIMDRMBA20191'   value='Pointer/Credit System'   onclick="hideDiv(this,'Percent System');showPersonalDiv();"  onmouseover="showTipOnline('As per University Marksheet',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('As per University Marksheet',this);" onmouseout="hidetip();" > Pointer / Credit System</span>&nbsp;&nbsp;
				<?php if(isset($MarkingSystemIMDRMBA2019) && $MarkingSystemIMDRMBA2019!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["MarkingSystemIMDRMBA2019"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $MarkingSystemIMDRMBA2019;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarkingSystemIMDRMBA2019_error'></div></div>
				</div>
				</div>
			</li>
		</div>

		<div id = 'MarkingSystem_Percent System' style="display: none;">
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Marks Obtained<span>*</span>: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MarksObtainedIMDRMBA2019' id='MarksObtainedIMDRMBA2019'  validate="validateInteger"     caption="marks obtained"   minlength="1"   maxlength="4"     tip="Please provide your aggregate marks"   value=''   />
				<?php if(isset($MarksObtainedIMDRMBA2019) && $MarksObtainedIMDRMBA2019!=""){ ?>
				  <script>
				      document.getElementById("MarksObtainedIMDRMBA2019").value = "<?php echo str_replace("\n", '\n', $MarksObtainedIMDRMBA2019 );  ?>";
				      document.getElementById("MarksObtainedIMDRMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarksObtainedIMDRMBA2019_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Total Marks<span>*</span>: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='TotalMarksIMDRMBA2019' id='TotalMarksIMDRMBA2019'  validate="validateInteger"      caption="total marks"   minlength="1"   maxlength="4"      value=''   />
				<?php if(isset($TotalMarksIMDRMBA2019) && $TotalMarksIMDRMBA2019!=""){ ?>
				  <script>
				      document.getElementById("TotalMarksIMDRMBA2019").value = "<?php echo str_replace("\n", '\n', $TotalMarksIMDRMBA2019 );  ?>";
				      document.getElementById("TotalMarksIMDRMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'TotalMarksIMDRMBA2019_error'></div></div>
				</div>
				</div>
			</li>
		</div>

		<div id = 'MarkingSystem_Pointer/Credit System' style="display: none;">
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Equivalent Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='EquivalentPercentageIMDRMBA2019' id='EquivalentPercentageIMDRMBA2019'  validate="validateFloat"    caption="Equivalent Percentage"   minlength="1"   maxlength="100"     tip="Please provide equivalent percentage for pointer/credit/grade obtained  "   value=''   />
				<?php if(isset($EquivalentPercentageIMDRMBA2019) && $EquivalentPercentageIMDRMBA2019!=""){ ?>
				  <script>
				      document.getElementById("EquivalentPercentageIMDRMBA2019").value = "<?php echo str_replace("\n", '\n', $EquivalentPercentageIMDRMBA2019 );  ?>";
				      document.getElementById("EquivalentPercentageIMDRMBA2019").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'EquivalentPercentageIMDRMBA2019_error'></div></div>
				</div>
				</div>
			</li>
		</div>

			<li>
				<h3>Declaration</h3>
				<div class='fieldBoxLarge' style="width: 100% ; color: #666666; font-style: italic;">
					<ul>
						<li>
							All entries made in the application form are true to the best of my knowledge and belief. I am willing to produce original certificates on demand at any time. I also undertake that I shall abide by the rules and regulations of Deccan Education Society- Institute of Management Development and Research (IMDR).
						</li>
					</ul>
				</div>

				<div class="additionalInfoLeftCol">

					<label style="width: 100%; text-align: left;">
						<input type='checkbox' validate='validateChecked'  required="true" caption ='Please accept the terms'   name='IMDRMBA2019Terms[]' id='IMDRMBA2019Terms'   value='1'  checked   onmouseout="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();">
							
						</input>I agree to the above terms and conditions
						<span onmouseout="showTipOnline('Please check to accept terms',this);"
						 onmouseout="hidetip()"></span>&nbsp;&nbsp;
				
				<?php if(isset($IMDRMBA2019Terms) && $IMDRMBA2019Terms!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["IMDRMBA2019Terms[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$IMDRMBA2019Terms);
						    for($x=0;$x<count($arr);$x++){ ?>
							  if(objCheckBoxes[i].value == "<?php echo $arr[$x];?>") {
								  objCheckBoxes[i].checked = true;
							  }
					      <?php
						    }
					      ?>
				    }
				</script>
			      <?php } ?>
				
						<div style='display:none'>
							<div class='errorMsg' id= 'IMDRMBA2019Terms_error'>
								
							</div>
						</div>
					</label>
				</div>
			</li>




		</ul>
	</div>
</div><script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script><?php if(isset($city) && $city!=""){ ?>
    <script>
	var selObj = document.getElementById("city"); 
	if(selObj){
	      var A= selObj.options, L= A.length;
	      while(L){
		  L = L-1;
		  if (A[L].innerHTML == "<?php echo $city;?>" || A[L].value == "<?php echo $city;?>"){
		      selObj.selectedIndex= L;
		      L= 0;
		  }
	      }
	}
    </script>
  <?php } ?><?php if(isset($Ccity) && $Ccity!=""){ ?>
    <script>
	var selObj = document.getElementById("Ccity"); 
	if(selObj){
	      var A= selObj.options, L= A.length;
	      while(L){
		  L = L-1;
		  if (A[L].innerHTML == "<?php echo $Ccity;?>" || A[L].value == "<?php echo $Ccity;?>"){
		      selObj.selectedIndex= L;
		      L= 0;
		  }
	      }
	}
    </script>
  <?php } ?><script>


  (function()
  {
	if("<?php echo $QualifyingStatusIMDRMBA2019;?>"=="Completed" || !"<?php echo $QualifyingStatusIMDRMBA2019;?>"){
  		document.getElementById('degree_Completed').style.display = 'block';
  		var elem = document.getElementsByName('MarkingSystemIMDRMBA2019')
  		for (var i = elem.length - 1; i >= 0; i--) {
  			console.log(elem[i]);
  			elem[i].setAttribute('required','true');
  		}
  		if ("<?php echo $MarkingSystemIMDRMBA2019;?>"=="Percent System" || !"<?php echo $MarkingSystemIMDRMBA2019;?>"){
  			document.getElementById('MarkingSystem_Percent System').style.display = 'block';
  			document.getElementById('MarksObtainedIMDRMBA2019').setAttribute('required','true');
  			document.getElementById('TotalMarksIMDRMBA2019').setAttribute('required','true');
  		}
  		else{
  			document.getElementById('MarkingSystem_Pointer/Credit System').style.display = 'block';
  			document.getElementById('EquivalentPercentageIMDRMBA2019').setAttribute('required','true');	
  		}
  	}
  	

  })();


  </script>

  <style>
  	#appsFormWrapper .fieldBoxLarge select{width: 214px;background: #fff;border: 1px solid #ccc;padding: 4px 0px}
  	#appsFormWrapper .fieldBoxLarge input[type='text']{border-radius: 2px;width: 73%;}
  	#appsFormWrapper .fieldBoxSmall input[type='text']{padding: 5px 2px;border-radius: 2px;}
  	#appsFormWrapper .formChildWrapper h3.upperCase, .formSection ul li h3{background: #f1f1f1;margin: 0 -10px;padding: 10px 15px;}
  	#appsFormWrapper h3.upperCase{    padding: 10px 10px;
    background: #f1f1f1;
    margin: 0 -10px;}
    .additionalInfoLeftCol label span{color: red;}
    .additionalInfoRightCol label span{color: red;}
    .errorMsg{pointer-events: none;}
    .fieldBoxLarge input[type="radio"], .fieldBoxLarge input[type="checkbox"] {
    position: relative;top: 2px;}
  </style>
