<?php

class AdminSitesController extends BaseController {

    public function getIndex() {
        return View::make('admin.sites.index');
    }

    public function getExport($validated) {
        header("Content-type: application/vnd.ms-excel; name='excel'; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"adtomatik_" . $validated . "_sites.xls\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        if ($validated == 'validated') {
            if (Utility::hasPermission('sites.all'))
                $sites = Site::getValidatedSites();
            else
                $sites = Site::getValidatedSites(Session::get('admin.id'));
        } else {
            if (Utility::hasPermission('sites.all'))
                $sites = Site::getUnvalidatedSites();
            else
                $sites = Site::getUnvalidatedSites(Session::get('admin.id'));
        }
        return View::make('admin.export.sites', ['sites' => $sites, 'validated' => $validated]);
    }

    public function loadSitesTable() {
        if (Utility::hasPermission('sites.all'))
            $sites = Site::getValidatedSites();
        else
            $sites = Site::getValidatedSites(Session::get('admin.id'));
        return View::make('admin.tables.tbl_sitios', ['sites' => $sites]);
    }

    public function loadUnvalidatedSitesTable() {
        if (Utility::hasPermission('sites.all'))
            $sites = Site::getUnvalidatedSites();
        else
            $sites = Site::getUnvalidatedSites(Session::get('admin.id'));
        return View::make('admin.tables.tbl_unvalidated_sitios', ['sites' => $sites]);
    }

    public function getSiteCategories($idSite) {
        $site = Site::find($idSite);
        $categories = Category::getAdserverNotDefaultCategories($site->getFirstAdserverId());
        $selectedCategories = $site->categories;
        return View::make('admin.sites.categorize', ['site' => $site, 'categories' => $categories, 'selectedCategories' => $selectedCategories]);
    }

    public function getSiteData($idSite) {
        $site = Site::find($idSite);
        return View::make('admin.sites.view', ['site' => $site]);
    }

    public function getDomainList($idSite) {
        $site = Site::find($idSite);
        $domains = explode("\n", $site->getDomainList());
        $urlArray = array();
        foreach ($domains as &$domain) {
            $urlArray[] = trim($domain);
        }
        return View::make('admin.publishers.siteDomains', ['domains' => $urlArray, 'site' => $site]);
    }

    public function categorize($idSite, $selectedCategories) {
        $site = Site::find($idSite);
        Site::updateSiteSetCategorizedFalse($idSite);
        $categoriesId = explode('_', $selectedCategories);
        $categories = array();
        foreach ($categoriesId as $id) {
            $categories[] = Category::find($id);
        }
        $site->categories()->detach();
        $site->categories()->saveMany($categories);
    }

    public function updateSiteData($idSite) {
        try {
            // buscamos el sitio
            $site = Site::find($idSite);
            $site->sit_state = Input::get('sit_state');

            $site->forceSave();

            if (Input::get('sit_state')) {
                $email = $site->publisher->user->getEmail();;
                $data = array('content' => 'The website ' . $site->sit_name . ' has been verified.');
                Mailer::send('emails.blank', $data, $email, '', 'Your website has been validated');
            }

            if (Request::ajax())
                return Response::json(['error' => 0]);

            return Redirect::back();
        } catch (Exception $ex) {
            if (Request::ajax())
                return Response::json(['error' => 2, 'messages' => $ex->getMessage()]);

            return Redirect::back();
        }
    }

}
