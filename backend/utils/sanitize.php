<?php
function sanitizeString($string)
{
    return htmlspecialchars(trim($string), ENT_QUOTES, 'UTF-8');
}
?>