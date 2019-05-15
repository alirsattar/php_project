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

        $this->jsonObj = new \stdClass;

        if( (isset($this->_orgChartByHierarchy)) && (!empty($this->_orgChartByHierarchy)) ) {
            $title = $this->_orgChartByHierarchy;
        } else {
            $this->_orgChartByHierarchy = $default_orgChartByHierarchy;
        }
        
        // Get librarians associated with this guide
        $this->_staff = $this->getLibraryStaff($this->_subject_id);
        $this->_data_array = json_decode($this->_extra, true);
    }

    protected function onViewOutput()
    {
        $this->_body = $this->loadHtml(__DIR__ . '/views/OrgChartView.php' );
    }

    protected function onEditOutput() {

        $body_before_ckEditor = $this->_body;
        $this->_body = $this->loadHtml(__DIR__ . '/views/OrgChartEdit.php' );

        //get ckEditor
        $ckEditor = $this->getCkEditor($body_before_ckEditor);

        $this->_body .= '<hr>' . $ckEditor;

    }

    /**
     * @param Number Subject ID
     */
    protected function getLibraryStaff($subject_id) {

        // Grab selected staff members
        $querier = new Querier();

        $qs = " SELECT fname, lname
                FROM staff
                WHERE staff_id <> 1
                ORDER BY supervisor_id";

        $this->allStaff = $querier->query($qs);

        $stafferId = 91512;
        // Hard coded 91512 (Tristan Clark) in for now -- but is set up such that it could hadnle searches for a specific staffer by ID (and/or name)
        // $qs = " SELECT staff_id, fname, lname, supervisor_id
        //         FROM staff
        //         WHERE supervisor_id IN (
        //             SELECT staff_id
        //             FROM staff
        //             WHERE supervisor_id = $stafferId
        //             )
        //         OR supervisor_id = $stafferId
        //         OR staff_id = $stafferId;";

        $deptId = 23994;
        $qs = " SELECT staff_id, fname, lname, supervisor_id, email
                FROM staff
                WHERE department_id = $deptId;";

        $selectedStaff = $querier->query($qs);

        $this->jsonObj = json_encode($selectedStaff);

        return $selectedStaff;
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
        return _('Organizational Chart');
    }

}