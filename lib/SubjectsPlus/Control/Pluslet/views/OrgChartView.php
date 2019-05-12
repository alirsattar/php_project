<?php
/**
 * Created by Ali Sattar
 * User: alirsattar
 * Date: 05/11/2019
 * Time: 10:54 PM
 */
?>


<div>
    <h1>TEST</h1>
    <?php
    $settings2 = array();
    foreach($this->_staff as $staffer):
    $settings2 = $this->getOrgChartStaffSettings($staffer, $this->_array_keys, $this->_data_array);
    $this->setSubjectSpecialist($settings2);
        echo "<p>" . $this->staff_id . "</p>";
    endforeach;
    ?>
</div>

<div class="org-chart pure-g" data-pluslet-id="<?php echo $this->_pluslet_id; ?>">

<?php
    $settings = array();
    foreach($this->_staff as $staff):
        $settings = $this->getOrgChartStaffSettings($staff, $this->_array_keys, $this->_data_array);
        $this->setSubjectSpecialist($settings);
?>

    <div class="subject-specialists pure-u-md-1-5" data-staff-id="<?php echo $this->staff_id; ?>">
        <div class="specialist-photo" data-show-photo="<?php echo $this->showPhoto; ?>" ><img src="<?php echo $this->img_url;?>" /> </div>
        <div class="specialist-info show-photo-full">
            <h4 data-show-name="<?php echo $this->showName; ?>"><?php echo $this->fullname; ?></h4>
            <ul class="staff-details">
                <li data-show-title="<?php echo $this->showTitle; ?>"><?php echo $this->title; ?></li>
                <li data-show-email="<?php echo $this->showEmail; ?>"><a href="mailto:<?php echo $this->email; ?>"><?php echo $this->email; ?></a></li>
                <li data-show-phone="<?php echo $this->showPhone; ?>"> ----------------- </li>
                <li>
                    <div class="staff-social" data-show-facebook="<?php echo $this->showFacebook; ?>">
                        <a href="https://facebook.com/<?php echo $this->facebook; ?>"><i class="fa fa-facebook"></i></a>
                    </div>
                    <div class="staff-social" data-show-twitter="<?php echo $this->showTwitter ; ?>">
                        <a href="https://twitter.com/<?php echo $this->twitter; ?>"><i class="fa fa-twitter"></i></a>
                    </div>
                    <div class="staff-social" data-show-pinterest="<?php echo $this->showPinterest; ?>">
                        <a href="https://pinterest.com/<?php echo $this->pinterest; ?>"><i class="fa fa-pinterest"></i></a>
                    </div>
                    <div class="staff-social" data-show-instagram="<?php echo $this->showInstagram; ?>">
                        <a href="https://instagram.com/<?php echo $this->instagram; ?>"><i class="fa fa-instagram"></i></a>
                    </div>
                </li>
            </ul>
        </div>
    </div>

<?php endforeach; ?>

<div class="org-chart-description">
        <?php echo $this->_body; ?>
    </div>
</div>