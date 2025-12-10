<?php

// Function designed to handle missing data within a dataset. 
// Should be called when displaying any data incase there is missing data.

function safeDisplay($data, $placeholder = 'N/A') {
    if ($data === null || trim($data) === '') {
        return $placeholder;
    }

    return htmlspecialchars($data);
}

?>
