<?php
/**
 * Created by PhpStorm.
 * User: kushagra
 * Date: 2/8/18
 * Time: 6:17 PM
 */

?>
<div class="student-detail-col clearfix">
    <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite minus-icon flRt"></i>Optional fields </h3>
    <div class="multi-div optionl-dv">
        <div class="table-col">

            <div class="lft-side-col">
                <p>Do you have any specific universities in mind?</p>
                <textarea name="specificUniversities" id="specificUniversities" class="cms-textarea" style="width:192px;" maxlength="1000" minlength="1" validationType="str" caption="Do you have any specific universities in mind?"  onblur="showErrorMessage(this, '')"><?php echo($introFields['jsonData']['specificUniversities']);?></textarea>
                <div style="display: none" class="errorMsg" id="specificUniversities_error">error shown here</div>
            </div>
        </div>
        <div class="table-col">

            <div class="lft-side-col">
                <p>Work Experience/Internship</p>
                <textarea name="experience" id="experience" class="cms-textarea" caption="Work Experience/Internship" style="width:192px;" maxlength="1000" minlength="1" validationType="str"  onblur="showErrorMessage(this, '')"><?php echo($introFields['jsonData']['experience']);?></textarea>
                <div style="display: none" class="errorMsg" id="experience_error">error shown here</div>
            </div>
        </div>
        <div class="table-col">

            <div class="lft-side-col">
                <p>Extra Curricular</p>
                <textarea class="cms-textarea" name="extracurricular" id="extracurricular" caption="Extra Curricular" style="width:192px;" caption="Extracurricular" maxlength="1000" minlength="1" validationType="str" onblur="showErrorMessage(this, '')"><?php echo($introFields['jsonData']['extracurricular']);?></textarea>
                <div class="errorMsg" id="extracurricular_error"></div>
            </div>
        </div>
        <div class="table-col">

            <div class="lft-side-col">
                <p>College Projects/Research Papers</p>
                <textarea class="cms-textarea" name="projectsAndResearch" id="projectsAndResearch" caption="College Projects/Research Papers" style="width:192px;" maxlength="1000" minlength="1" validationType="str"  onblur="showErrorMessage(this, '')"><?php echo($introFields['jsonData']['projectsAndResearch']);?></textarea>
                <div style="display: none" class="errorMsg" id="projectsAndResearch_error">error shown here</div>
            </div>
        </div>

        <div class="table-col">

            <div class="lft-side-col">
                <p>Referee Detail</p>
                <textarea class="cms-textarea" name="refreeDetail" id="refreeDetail" caption="Referee Detail" style="width:192px;" maxlength="1000" minlength="1" validationType="str"  onblur="showErrorMessage(this, '')"><?php echo($introFields['jsonData']['refreeDetail']);?></textarea>
                <div style="display: none" class="errorMsg" id="refreeDetail_error">error shown here</div>
            </div>
        </div>

        <div class="table-col">

            <div class="lft-side-col">
                <p>Relative in desired country</p>
                <textarea class="cms-textarea" name="relativeDetail" id="relativeDetail" caption="Relative in desired country" style="width:192px;" maxlength="1000" minlength="1" validationType="str"  onblur="showErrorMessage(this, '')"><?php echo($introFields['jsonData']['relativeDetail']);?></textarea>
                <div style="display: none" class="errorMsg" id="relativeDetail_error">error shown here</div>
            </div>
        </div>

        <div class="table-col">

            <div class="lft-side-col">
                <p>For no exam, booked exam date</p>
                <textarea class="cms-textarea" name="bookedExamDate" id="bookedExamDate" caption="Booked exam date" style="width:192px;" maxlength="1000" minlength="1" validationType="str"  onblur="showErrorMessage(this, '')"><?php echo($introFields['jsonData']['bookedExamDate']);?></textarea>
                <div style="display: none" class="errorMsg" id="bookedExamDate_error">error shown here</div>
            </div>
        </div>
        <div class="table-col">

            <div class="lft-side-col">
                <p>Additional notes</p>
                <textarea class="cms-textarea" name="additionalNotes" id="additionalNotes" caption="Additional notes" style="width:192px;" maxlength="1000" minlength="1" validationType="str"  onblur="showErrorMessage(this, '')"><?php echo($introFields['jsonData']['additionalNotes']);?></textarea>
                <div style="display: none" class="errorMsg" id="additionalNotes_error">error shown here</div>
            </div>
        </div>


    </div>
</div>

<div class="student-detail-col">
    <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite minus-icon flRt"></i>Finance fields </h3>
    <div class="multi-div optionl-dv">
        <div class="table-col">

            <div class="lft-side-col">
                <p>Self funding amount</p>
                <textarea class="cms-textarea" name="selfFundingAmount" id="selfFundingAmount" style="width:192px;" maxlength="1000" minlength="1" validationType="str" caption="Self funding amount"  onblur="showErrorMessage(this, '')"><?php echo($introFields['jsonData']['selfFundingAmount']);?></textarea>
                <div style="display: none" class="errorMsg" id="selfFundingAmount_error">error shown here</div>
            </div>
        </div>
        <div class="table-col">

            <div class="lft-side-col">
                <p>Sponsor</p>
                <textarea class="cms-textarea" name="sponsor" id="sponsor" style="width:192px;" maxlength="1000" minlength="1" validationType="str"  caption="Sponsor" onblur="showErrorMessage(this, '')"><?php echo($introFields['jsonData']['sponsor']);?></textarea>
                <div style="display: none" class="errorMsg" id="sponsor_error">error shown here</div>
            </div>
        </div>
        <div class="table-col">

            <div class="lft-side-col">
                <p>Loan funding amount</p>
                <textarea class="cms-textarea" name="loanFundingAmount" id="loanFundingAmount" style="width:192px;" maxlength="1000" minlength="1" validationType="str" caption="Loan funding amount" onblur="showErrorMessage(this, '')"><?php echo($introFields['jsonData']['loanFundingAmount']);?></textarea>
                <div style="display: none" class="errorMsg" id="loanFundingAmount_error">error shown here</div>
            </div>
        </div>
        <div class="table-col">

            <div class="lft-side-col">
                <p>Budget for applications</p>
                <textarea class="cms-textarea" name="budgetApplication" id="budgetApplication" style="width:192px;" maxlength="1000" minlength="1" validationType="str" caption="Budget for applications" onblur="showErrorMessage(this, '')"><?php echo($introFields['jsonData']['budgetApplication']);?></textarea>
                <div style="display: none" class="errorMsg" id="budgetApplication_error">error shown here</div>
            </div>
        </div>

        <div class="table-col">

            <div class="lft-side-col">
                <p>Parents income and job</p>
                <textarea class="cms-textarea" name="parentsJob" id="parentsJob" style="width:192px;" maxlength="1000" minlength="1" caption="Parents income and job" validationType="str"  onblur="showErrorMessage(this, '')"><?php echo($introFields['jsonData']['parentsJob']);?></textarea>
                <div style="display: none" class="errorMsg" id="parentsJob_error">error shown here</div>
            </div>
        </div><div class="table-col">

            <div class="lft-side-col">
                <p>Status on financial documents</p>
                <textarea class="cms-textarea" name="finacialDocsStatus" id="finacialDocs" style="width:192px;" maxlength="1000" minlength="1" caption="Status on financial documents" validationType="str"  onblur="showErrorMessage(this, '')"><?php echo($introFields['jsonData']['finacialDocsStatus']);?></textarea>
                <div style="display: none" class="errorMsg" id="finacialDocs_error">error shown here</div>
            </div>
        </div>
        <div class="table-col">

            <div class="lft-side-col">
                <p>Collateral</p>
                <textarea class="cms-textarea" name="collateral" id="collateral" style="width:192px;" maxlength="1000" minlength="1" caption="Collateral" validationType="str"  onblur="showErrorMessage(this, '')"><?php echo($introFields['jsonData']['collateral']);?></textarea>
                <div style="display: none" class="errorMsg" id="collateral_error">error shown here</div>
            </div>
        </div>

    </div>
</div>
