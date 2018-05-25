<?php

class AdminConstantsController extends BaseController {
    /*
     *
     * GETs
     *  
     */

    /*
     * Muestra la pantalla de mis pagos
     */

    public function getIndex() {
        //$constant_groups = Constant::where('cns_description', '<>', '')->get();
        $constant_groups = ConstantGroup::all();
        return View::make('admin.constants.index', ['constant_groups' => $constant_groups]);
    }

    /*
     *
     * SETs
     *  
     */

    public function changeConstant($constantId) {
        try {
            if (Input::get('cns_value') == "") {
                if (Request::ajax())
                    return Response::json(['error' => 1, 'messages' => 'El valor no puede quedar nulo!']);
                return Redirect::back();
            }
            $constant = Constant::find($constantId);
            $constant->setValue(Input::get('cns_value'));
            if (!$constant->save()) {
                if (Request::ajax())
                    return Response::json(['error' => 1, 'messages' => $constant->errors()]);
                return Redirect::back();
            }
            return Response::json(['error' => 0]);
        } catch (Exception $ex) {
            if (Request::ajax())
                return Response::json(['error' => 2, 'messages' => $ex->getMessage()]);
            return Redirect::back();
        }
    }

}
