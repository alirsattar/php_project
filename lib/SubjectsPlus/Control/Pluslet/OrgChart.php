<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 8/25/15
 * Time: 12:55 PM
 */

namespace SubjectsPlus\Control;
require_once("Pluslet.php");

class Pluslet_OrgChart extends Pluslet {

    public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
        parent::__construct($pluslet_id, $flag, $subject_id, $isclone);

        $this->_type = "OrgChart";

        $this->_subject_id = $subject_id;
        
        $this->_pluslet_id = $pluslet_id;

        // What kind of hierarchy should be used
        global $default_orgChartByHierarchy;

        if( (isset($this->_orgChartByHierarchy)) && (!empty($this->_orgChartByHierarchy)) ) {
            $title = $this->_orgChartByHierarchy;
        } else {
            $this->_orgChartByHierarchy = $default_orgChartByHierarchy;
        }
        
        // Get librarians associated with this guide
        $this->_staff = $this->getAllLibraryStaff($this->_subject_id);
        $this->_data_array = json_decode($this->_extra, true);

        $this->_array_keys = array('Name', 'Photo', 'Title', 'Email', 'Phone', 'Facebook', 'Twitter', 'Pinterest', 'Instagram');
    }

    protected function onViewOutput()
    {
        $this->_body = $this->loadHtml(__DIR__ . '/views/OrgChartView.php' );
    }


    protected function onEditOutput() {

        $body_before_ckEditor = $this->_body;
        $this->_body = $this->loadHtml(__DIR__ . '/views/OrgChartView.php' );

        //get ckEditor
        $ckEditor = $this->getCkEditor($body_before_ckEditor);

        $this->_body .= '<hr>' . $ckEditor;

    }


    public function setSubjectSpecialist(array $settings) {
        $this->staff_id      = $settings['staff_id'];
        $this->fullname      = $settings['fname'].' '.$settings['lname'];
        $this->title         = $settings['title'];
        $this->email         = $settings['email'];
        $this->tel           = $settings['tel'];
        $this->img_url       = $settings['img_url'];
        $this->facebook      = $settings['facebook'];
        $this->twitter       = $settings['twitter'];
        $this->pinterest     = $settings['pinterest'];
        $this->instagram     = $settings['instagram'];
        $this->showName      = $settings['showName'];
        $this->showPhoto     = $settings['showPhoto'];
        $this->showTitle     = $settings['showTitle'];
        $this->showEmail     = $settings['showEmail'];
        $this->showPhone     = $settings['showPhone'];
        $this->showFacebook  = $settings['showFacebook'];
        $this->showTwitter   = $settings['showTwitter'];
        $this->showPinterest = $settings['showPinterest'];
        $this->showInstagram = $settings['showInstagram'];
    }

    protected function getOrgChartStaffSettings($staff, $show_keys, $data) {

        $social = $this->getStaffSocialMedia($staff['staff_id']);

        $truncated_email = explode("@", $staff['email']);
        if(isset($_GET['subject'])) {
            $staff_picture_url = "../assets/users/_" . $truncated_email[0] . "/headshot.jpg";
        } elseif(isset($_GET['subject_id'])) {
            $staff_picture_url = $this->_relative_asset_path . "users/_" . $truncated_email[0] . "/headshot.jpg";
        } else {
            $staff_picture_url = $this->_relative_asset_path . "users/_" . $truncated_email[0] . "/headshot.jpg";
        }


        $settings = array(
            'staff_id'      => $staff['staff_id'],
            'fname'         => $staff['fname'],
            'lname'         => $staff['lname'],
            'title'         => $staff['title'],
            'email'         => $staff['email'],
            'tel'           => $staff['tel'],
            'img_url'       => $staff_picture_url,
            'facebook'      => $social['facebook'],
            'twitter'       => $social['twitter'],
            'pinterest'     => $social['pinterest'],
            'instagram'     => $social['instagram'],
            'showName'      => isset($showStatusSettings['showName'])  ? $showStatusSettings['showName'] : "No",
            'showPhoto'     => isset($showStatusSettings['showPhoto'])  ? $showStatusSettings['showPhoto'] : "No",
            'showTitle'     => isset($showStatusSettings['showTitle'])  ? $showStatusSettings['showTitle'] : "No",
            'showEmail'     => isset($showStatusSettings['showEmail'])  ? $showStatusSettings['showEmail'] : "No",
            'showPhone'     => isset($showStatusSettings['showPhone'])  ? $showStatusSettings['showPhone'] : "No",
            'showFacebook'  => isset($showStatusSettings['showFacebook'])  ? $showStatusSettings['showFacebook'] : "No",
            'showTwitter'   => isset($showStatusSettings['showTwitter'])   ? $showStatusSettings['showTwitter'] : "No",
            'showPinterest' => isset($showStatusSettings['showPinterest']) ? $showStatusSettings['showPinterest'] : "No",
            'showInstagram' => isset($showStatusSettings['showInstagram']) ? $showStatusSettings['showInstagram'] : "No"
        );
        return $settings;
    }

    protected function getStaffSocialMedia($staff_id) {
        $staff = $this->getEditorData($staff_id);
        $data = json_decode(html_entity_decode( $staff[0]['social_media'] ), true);
        return $data;
    }

    protected function getAllLibraryStaff($subject_id) {

        // Grab all staff for entire library system
        $querier = new Querier();
        $qs = "SELECT *
                    FROM staff
                    WHERE staff_id <> 1
                    ORDER BY supervisor_id";

        $allStaff = $querier->query($qs);
        return $allStaff;
    }

    protected function getEditorData($staffId) {
        $querier = new Querier();
        $qs = "SELECT lname, fname, email, tel, title, extra, social_media
                FROM staff
                WHERE staff_id = {$staffId}";
        $editorData = $querier->query($qs);
        return $editorData;
    }

    protected function getCkEditor($body_before_ckEditor) {

        global $CKPath;
        global $CKBasePath;

        include ($CKPath);
        global $BaseURL;


        $oCKeditor = new CKEditor($CKBasePath);
        $oCKeditor->timestamp = time();
        $oCKeditor->returnOutput = true;
        //$oCKeditor->config['ToolbarStartExpanded'] = true;
        $config['toolbar'] = 'TextFormat';
        $config['height'] = '300';
        $config['filebrowserUploadUrl'] = $BaseURL . "ckeditor/php/uploader.php";

        $this_instance = "editor-specialist";
        $ckEditor = $oCKeditor->editor($this_instance,  $body_before_ckEditor, $config);
        return $ckEditor;
    }

    static function getMenuIcon() {
        $icon="<i class=\"fa fa-sitemap\" title=\"" . _("Organizational Chart") . "\" ></i><span class=\"icon-text\">" . _("Organizational Chart") ."</span>";
        return $icon;
    }

    static function getMenuName() {
        return _('Subject Specialist');
    }

}