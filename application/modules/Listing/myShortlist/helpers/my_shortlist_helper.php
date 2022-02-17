<?php

function getCoursesShortlistCount($courseId)
{
    $courseId = $courseId%100;
    $courseId += 113;
    $courseId = $courseId%400;
    return $courseId;
}