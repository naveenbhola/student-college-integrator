<?php $this->load->view('shikshaSchema/header'); ?>

<h1>Tags Extraction Process Flow</h1>

<div style='margin-bottom: 40px;'>
<div class='desc'>Tags Code uses Natural Language Processing (NLP) along with QER (Query Entity Recognition) for Tags Deduction from the input sentence.   QER is used to detect the objective and background part of the sentence, and after that using NLP, tags are extracted from the sentence.<br/><br/>The brief description of complete process is :</div>
</div>

<div style="margin-bottom: 30px;">
    <a href='/public/images/appDoc/image001.png'>
        <img src='/public/images/appDoc/image001.png' width='440' height="800"/>
    </a>
</div>


<div class='desc'>The complete / detailed flow of process is described below :

            <ol>
                <li>On Start-up, fetch all the <b>tags from database</b> to store them in memory for matching.</li>
                <li>Load all the <b>models of NLP (Apache and Stanford) into memory</b></li>
                <li>Tags Varients are kept in memory in both <b>original form and stemmed form</b> (Stemming is done using <b>Porter Alogrithm</b>).</li>
                <li>As soon as the request is received for Tagging by Tomcat Server(for Tagging - a Java Servlet Project ), below steps are followed  :
                        <ul style="margin-top:20px;">
                                <li>Clean the sentence (Special Characters Handling). Manual <b>Hardcoded cleaning</b> is done for <b>LL.B and PH.D</b> like cases, as cleaning this dynamically could result in noise.</li>
                                <li>Request is sent to <b>QER (Query Entity Recognition) to detect the objective and background part</b>.</li>
                                <li>On both objective and background part, we follow steps :
                                        <ol type="i" style="margin-top:20px;">
                                                <li><b>Extract sentences</b> from the objective / background part (Using NLP).</li>
                                                <li>From each sentence, <b>detect Noun Phrases using Parse Trees (Using NLP)</b> and there n-grams for smaller length nouns from bigger nouns.</li>
                                                <li>From extracted nouns, we <b>match them with tags data from database.</b></li>
                                                <li><b>Remove</b> all the <b>substring nouns from the bigger noun</b> (checked based on index of words in sentence, not on words)</li>
                                                <li>In case of a matched <b>synonym, replace it with original words (tags)</b>.</li>
                                                <li>Then, detect all the <b>dependencies in the sentences</b> between the words using the <b>Stanford Dependency Parsing</b>.</li>
                                                <li>From the extracted depedencies, we <b>create combinations and match them with tags</b>.</li>
                                                <li>Also to match varients, <b>varients specific combinations are created</b> to match with the tags.</li>
                                        <ol>
                                </li>
                        </ul>
                </li>
                <li>Return the match tags</li>
            </ol>

</div>
<div class='desc'><b>Uses :</b> Java 8(for Stanford NLP), Apache Open NLP & Stanford CoreNLP(only dependency Parser is used), MySQL(For Database )</div>

<?php $this->load->view('shikshaSchema/footer'); ?>

