<?php
if(is_array($userPointDetail) && count($userPointDetail)>0){
	foreach ($userPointDetail['result'] as $value) {
    		$html ='<tr id="_'.$value['userid'].'">';
			$html.='<td>'.$value['userid'].'</td>';
			$html.='<td>'.$value['firstname'].'&nbsp'.$value['lastname'].'</td>';
			$html.='<td><a href="'.SHIKSHA_HOME."/getUserProfile/".$value['displayname'].'" target="_blank" title="Profile page">'.$value['displayname'].'</a></td>';
			$html.='<td id="prev-'.$value['userid'].'">'.$value['previouspoints'].'</td>';
			$html.='<td id="current-'.$value['userid'].'">'.$value['points'].'</td>';
			$html.='<td id="btn-'.$value['userid'].'"><a href="javascript:void(0);" data-userId="'.$value['userid'].'" id="edit-'.$value['userid'].'" class="editP">Edit</a></td>';
		    $html.='</tr>';
		    echo $html;
    }
}?>