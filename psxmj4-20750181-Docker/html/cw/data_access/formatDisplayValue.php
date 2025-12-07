<?php

function safeDisplay($data, $placeholder = 'N/A') {
    if ($data === null || trim($data) === '') {
        return $placeholder;
    }

    return htmlspecialchars($data);
}

?>
