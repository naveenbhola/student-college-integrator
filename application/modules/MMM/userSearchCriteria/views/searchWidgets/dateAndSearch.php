<div class="ext-div">
    <h3 class="clone edu-inTitle">Date Range when the interest was created by students</h3>
    <table class="user-table" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <input id="timeRangeDuration" class="timeRange" checked="checked" style="display:none" type="radio" />
                <div class="dt-rngDv"></div>
                <div class="to-block">
                    <div class="to-col">
                        <label>From:</label>
                        <input type="text" class="txt-ip" placeholder="dd/mm/yyyy" readonly="readonly" id="timeRangeDurationFrom" name="timeRangeDurationFrom"/>
                        <img src="/public/images/cal-icn.gif" id="timeRangeFromImage" style="vertical-align: middle; cursor:pointer;" />
                    </div>
                    <div class="to-col">
                        <label>To:</label>
                        <input type="text" class="txt-ip" placeholder="dd/mm/yyyy" value="<?php echo date('d/m/Y'); ?>" readonly="readonly" id="timeRangeDurationTo" name="timeRangeDurationTo"/>
                        <img src="/public/images/cal-icn.gif" id="timeRangeToImage" style="vertical-align: middle; cursor:pointer;" />
                    </div>
                    
                </div>
                <label id="timeRangeDurationError"></label>

            </td>
        </tr>
        <tr>
            <td>
                <div class="Customcheckbox2 in-vUsr" style="box-sizing:border-box;">
                    <input type="checkbox" id="activeUsers" name="includeActiveUsers" checked="checked"/>
                    <label for="activeUsers">Also show those relevant students who created interest <span style="font-weight: bold;">before</span> this date range, and just logged-in to Shiksha <span style="font-weight: bold;">during</span> this date range.</label>
                </div>
            </td>
        </tr>
    </table>
</div>
<div>
    <table cellpadding="0" cellspacing="0" class="user-table">
        <tr>
            <td class="nxt-td mrgLft-td" actionType="search">
                <a href="javascript:void(0);" id="searchUsers" class="cmp-btn-ldb">Search</a>
            </td>
        </tr>
        <tr>
            <td class="nxt-td mrgLft-td">
                <p id="totalUsersCount"></p>
            </td>
            
        </tr>
        
    </table>
</div>  