<?php if ($contentDir) :?>

<ul>

<?php 

foreach ($contentDir as $v) {
    echo '<li><a href="/?pat='.$v['link'].'">'.$v['name'].'</a></li>';
}

?>

</ul>


<?php endif; ?>