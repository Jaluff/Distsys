<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Compras extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('compras_model', 'compras');
        $this->load->model('proveedor_model', 'proveedores');
        $this->load->model('producto_model', 'productos');
        $this->load->model('tpv_model', 'tpvs');
        $this->load->library('form_validation');
        
        $this->_init();
    }
    private function _init()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }

    public function index()
    {
        $this->output->set_template('default');
        $this->data['proveedores'] = $this->proveedores->get_proveedores();
        $this->data['productos'] = $this->productos->get_productos();
        $this->data['tpv'] = $this->tpvs->get_tpv();
        //var_dump($this->data);
        $this->load->view('compras/compras_view', $this->data);
    }

    public function nueva()
    {
        $this->output->set_template('default');
        $this->data['proveedores'] = $this->compras->get_proveedores();
        $this->data['productos'] = $this->productos->get_productos();
        $this->data['tpv'] = $this->tpvs->get_tpv();
        //var_dump($this->data);
        $this->load->view('compras/nueva_compra', $this->data);
    }
    
    public function ajax_list()
    {
        $tpv = $this->input->post('tpv');
        $fecha = ($this->input->post('fecha') == '') ? $this->_data_first_month_day() : date('Y-m-d', strtotime($this->input->post('fecha')));
        $fecha_fin = ($this->input->post('fecha_fin') == '') ? $this->_data_last_month_day() : date('Y-m-d', strtotime($this->input->post('fecha_fin')));
        $list = $this->compras->get_datatables($tpv, $fecha, $fecha_fin);
        //echo $list['id_producto'];
        //var_dump($list); exit();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $compra) {
            $no++;
            $row = array();
            $row[0] = null;
            $row['id_compra'] = $compra->id_compra;
            $row['numero_compra'] = $compra->numero_compra;
            $row['proveedor'] = $compra->prov_nombre;
            $row['usuario'] = $compra->usuario;
            $row['estado'] = $compra->estado;
            $row['numero_compra'] = $compra->numero_compra;
            $row['importe'] = $compra->importe_total;
            $row['usuario'] = $compra->usuario;
            $row['compra_created_on'] = date("d-m-Y", strtotime($compra->compra_created_on));

            //add html for action
            $row['Acciones'] = '
            <div class="btn-group btn-group-sm">
            <a class="btn btn-md btn-info" href="'.base_url().'compras/ver_compra/'. $compra->id_compra .'" title="Ver compras" >
                <i class="glyphicon glyphicon-eye-open"></i> 
            </a>
            </div>
            ';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->compras->count_all($tpv, $fecha, $fecha_fin),
            "recordsFiltered" => $this->compras->count_filtered($tpv, $fecha, $fecha_fin),
            "data" => $data,

        );

        echo json_encode($output);
    }

    private function _data_last_month_day() { 
        $month = date('m');
        $year = date('Y');
        $day = date("d", mktime(0,0,0, $month+1, 0, $year));
        return date('Y-m-d', mktime(0,0,0, $month, $day, $year));
    }

    /** Actual month first day **/
    private function _data_first_month_day() {
    $month = date('m');
    $year = date('Y');
    return date('Y-m-d', mktime(0,0,0, $month, 1, $year));
    }
    
    public function actualizar_estado(){
        $estado = $this->input->post('estado');
        $id_compra = $this->input->post('id');
        $total_importe = $this->input->post('total');
        $items = $this->input->post('detalles');
        parse_str($items, $detalles);
        //var_dump($detalles);
        // if($estado == 'Aprobada'){
            if($this->compras->update_stock_compra($id_compra, $detalles,$total_importe, $estado)){
                echo json_encode(true);
            }else{
                echo json_encode(false);
            }
        // }elseif($estado == 'Arribada'){
            // $where = array('id_compra' => $id_compra );
            // $data = array('estado' => $estado, 'importe_total' => $total_importe);
            // if ($this->compras->update_estado_compra($where, $data)){
            //     echo json_encode(true);
            // }else{
            //     echo json_encode(false);
            // }
        // }     
    }

    public function get_producto_stock(){
        $id = $this->input->post('id');
        $tpv = $this->input->post('tpv');
        $stock = $this->productos->get_stock($id, $tpv);
        echo json_encode($stock);
    }

    public function get_producto_precio(){
        $id = $this->input->post('id');
        $precio = $this->productos->get_precio_by_id($id);
        echo json_encode($precio);
    }
    

    public function guardar_compra(){
        $detalles =  array();
        $id_compra = $this->input->post('id');
        $estado = $this->input->post('estado');
        $tpv = $this->input->post('tpv');
        $importe = $this->input->post("total");
        $compra =  $this->input->post('compra');
        $pago = $this->input->post('pago');
        $detalles = $this->input->post('detalles'); 
        
        parse_str($compra, $compra);
        parse_str($detalles, $detalles);
        parse_str($pago, $pago);
        // echo "detalles: ".$detalles['nombre'];
        //  var_dump($detalles);//exit();
        $recibido = floatval(($pago['recibido_cheque'] + $pago['recibido_tarjeta'] + $pago['recibido_efectivo'])) ;
        $saldo = $recibido  - $importe; 
        $user = $this->ion_auth->user()->row();    
            
            $datos_compra = array(
                'metodoPago'            => $pago['metodo_pago'],
                'pago_efectivo'         => $pago['recibido_efectivo'], //- $vuelto,
                'pago_tarjeta'          => $pago['recibido_tarjeta'],
                'pago_cheque'           => $pago['recibido_cheque'],
                'pago_cheque_banco'     => $pago['cheque_banco'],
                'pago_cheque_cuenta'    => $pago['cheque_cuenta'],
                'pago_cheque_numero'    => $pago['cheque_numero'],
                'importe_total'         => $importe,
                'importe_recibido'      => $recibido,
                'importe_saldo'         => $saldo,
                'tarjeta'               => $pago['tarjeta'],
                'id_proveedor'      => $compra['id_proveedor'],
                'fecha_compra'      => $compra['compra_fecha'] ,
                'numero_compra'     => $compra['numero_compra'],
                'fecha_compra'      => date("Y-m-d", strtotime($compra['compra_fecha'])),
                'remito'            => $compra['remito'],
                //'pedido_numero'     => $compra['numero_pedido'],
                'factura_numero'    => $compra['factura_numero'],
                'factura_fecha'     => date("Y-m-d", strtotime($compra['factura_fecha'])),
                'compra_id_tpv'     => $compra['tpv'],
                'estado'            => $estado,
                'importe_total'     => $importe,
                'compra_created_on' => date('Y-m-d H:i', now()),
                'usuario'           => $user->first_name. ", ".$user->last_name,
                'id_empresa'        =>$this->session->userdata('id_empresa'),
              );
        if($this->compras->get_by_id($id_compra) != ''){
            $this->compras->save_compra($datos_compra, $detalles, $id_compra);
        }else{
            // $this->compras->update_compra($where);
        }
        // if ($numero_compra = ){
        //     $where = array('id_compra' => $numero_compra);
             
        //     if(){
                echo 'true';
            // }
            
        // }     
    }

    public function ajax_edit($id, $tpv)
    {
        //$data = [];
        $data_producto =  $this->productos->get_by_id($id);
        $data_precio = $this->productos->get_precio_by_id($id);
        $data_stock = $this->compras->get_stock($id, $tpv);
        $data =  (object)array_merge((array)$data_producto, (array)$data_precio, (array)$data_stock);
        echo json_encode($data);
    }

    public function ajax_enviar_producto($id, $tpv)
    {
        $data_producto =  $this->productos->get_by_id($id);
        $data_tpv = $this->tpvs->get_tpv($tpv);
        $data_stock = $this->compras->get_stock($id, $tpv);
        $data =  (object)array_merge((array)$data_producto, (array)$data_tpv, (array)$data_stock);
        echo json_encode($data);
    }

    function add_item(){
        $datos_producto = array(
            "id_producto" => $_POST['product_id'],
            "cantidad" => $_POST['quantity'],
            "precio" => $_POST['price']
        );
        $articulo = $this->compras->agregar_producto($datos_producto);
        if($this->input->post('ajax') != '1'){
            //redirect('compras/nueva'); // If javascript is not enabled, reload the page with new data
        }else{
            echo json_encode($articulo); // 'true'; // If javascript is enabled, return true, so the cart gets updated
        }
    }
    // function add_cart_item(){
    //     if($this->compras->validate_add_cart_item() == TRUE){
    //       // Check if user has javascript enabled
    //       if($this->input->post('ajax') != '1'){
    //           redirect('compras'); // If javascript is not enabled, reload the page with new data
    //       }else{
    //           echo 'true'; // If javascript is enabled, return true, so the cart gets updated
    //       }
    //     }
    // }

    // function update_cart(){
    //     if($this->compras->validate_update_cart() == TRUE){
    //       if($this->input->post('ajax') != '1'){
    //         //redirect('tpv'); // If javascript is not enabled, reload the page with new data
    //         echo 'truee';
    //         //echo 'true';
    //       }else{
    //           echo 'true'; // If javascript is enabled, return true, so the cart gets updated
    //       }
    //     }
    //     //redirect('tpv');
    // }

    // function delete_item_cart(){

    //     if($this->compras->validate_delete_item() == TRUE){
          
    //       if($this->input->post('ajax') != '1'){
    //         //redirect('tpv'); // If javascript is not enabled, reload the page with new data
    //         echo 'truee';
    //         //echo 'true';
    //       }else{
    //           echo 'true'; // If javascript is enabled, return true, so the cart gets updated
    //       }
    //     }else{
    //         echo "no valido";
    //     }
    //     //redirect('tpv');
    // }

    // function show_cart(){
    //     $this->load->view('administracion/cart');
    // }

    // function empty_cart(){
    //     $this->cart->destroy();
    //     // $this->load->helper('url');
    //     // redirect('administracion/compras'); // Refresh te page
    // }

    function ver_compra($idcompra){
        $this->output->set_template('default');

        $this->data['compras'] = $compras = $this->compras->get_by_id($idcompra);
        $this->data['detalles'] = $this->compras->get_compras_detalles($idcompra);
        $this->data['proveedor'] =  $this->compras->get_proveedores($compras->id_proveedor);
        $this->data['productos'] = $this->productos->get_productos();
        $this->load->view('compras/mostrarCompra_view',  $this->data);

    }
}
