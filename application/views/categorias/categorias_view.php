<div class="row">
    <!-- <div class="panel panel-success"> -->
    <div class="col-md-12">
        <div class="col-md-5">
            <h3>categorias</h3>
        </div>
        <div class="col-md-1 pull-right"> </div>
    </div>
</div>
<div class="row">
    <hr class="hr_success">
    <!-- <p><?php echo lang('index_subheading'); ?></p> -->
    <!-- </div> -->
    <?php
 //    $user = $this->ion_auth->user()->row();
    //		echo $user->email;
    if (isset($message)) { ?>
    <div id="infoMessage" class="alert uk-alert-success" role="alert">
        <?php echo $message; ?>
    </div>
    <?php 
} ?>
    <button class="btn btn-success" onclick="agregar_categoria();"><i class="glyphicon glyphicon-plus"></i> Nueva categoria</button>
    <button class="btn btn-default" onclick="reload_table();"><i class="glyphicon glyphicon-refresh"></i> Actualizar</button>
    <br />
    <br />

    <table class="table table-bordered table-condensed " cellspacing="0" width="100%" id="clientes">
        <thead>
            <tr>
                <!--                        <th></th>-->
                <th class="hidden-xs">id</th>
                <th>Nombre</th>
                <th>Estado</th>
                <th style="min-width: 90px">Action</th>
            </tr>
        </thead>
    </table>
</div>

<?php require_once(APPPATH . 'views/categorias/form_categoria.php'); ?>
<script type="text/javascript">
    table = $('#clientes').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "createdRow": function ( row, data, index ) {
                console.log(data['categoria_estado']);
                if( data['categoria_estado'] === '0') {
                    $('td', row).eq(2).html('<h4><div class="label label-danger ">No activa</div></h4>');
                }
                if( data['categoria_estado'] === '1') {
                    $('td', row).eq(2).html('<h4><div class="label label-info  ">Activa</div></h4>');
                }
            },
        "columns": [{
                "data": "id_categoria",
                "className": "hidden-xs"
            },
            {
                "data": "categoria_nombre"
            },
            {
                "data": "categoria_estado"
            },
            {
                "data": "Acciones"
            }
        ],
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo base_url() ?>categoria/ajax_list",
            "type": "POST"
        }, //Set column definition initialisation properties.
        "columnDefs": [{

            "targets": [-1], //last column
            "orderable": false, //set not orderable

        }]
    });

    function reload_table() {
        table.ajax.reload(null, false); //reload datatable ajax
    }
</script>

<script type="text/javascript">
    $.validate({
        lang: 'es'
    });
</script> 