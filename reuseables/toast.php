<?php
if(isset($_GET['id'])) {
    echo '<div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-body">
       ' . $_GET['icon'] . '

              <span style="padding-left: 5px">'. $_GET['id'] . '</span> 
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>';
    unset($_GET);
}
