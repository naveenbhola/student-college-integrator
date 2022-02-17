<div class="find-field-row">
    <label>Desired Graduation Level :</label>
    <div class="formCont">
        <input type="radio" id="desiredCourseLevel_ug_abroad" onblur="validateDesiredLevel();" name="desiredCourseLevel" value="ug" onclick="toggleFormForDesiredCourseLevel('ug');"/> UG &nbsp; &nbsp;  
        <input type="radio" id="desiredCourseLevel_pg_abroad" onblur="validateDesiredLevel();" name="desiredCourseLevel" value="pg" onclick="toggleFormForDesiredCourseLevel('pg');"/> PG 
        <div class="clearFix"></div>
        <div>
        <div class="errorMsg" id="desiredCourseLevel_abroad_error"></div>
      </div>  
    </div>
</div>

