<div class="panel panel-pago ">
            <div class="panel-body ">
                
                <form name="form_pago" id="form_pago" method="post">
                    <div class="form-group">
                        <label for="metodo_pago" ><h4 style="color: white" class="text-center">Forma de pago</h4></label>
                        <?php //if ($ventas->metodoPago != ''){?>

                        	 <!-- <input type="text" name="metodo_pago" id="metodo_pago" value="<?php echo strtoupper($ventas->metodoPago);?>"  class="form-control" readonly="readonly"> -->
								<?php //echo ($ventas->estado == 'cobrada') ? 'readonly="readonly"': '' ?>
                        <?php //}else{ ?>
                        	<select name="metodo_pago" id="metodo_pago" class="form-control">
                                <option value='ninguno'>Ninguno</option>
                                <option value='efectivo' <?php echo ( isset($ventas) && $ventas->metodoPago == 'efectivo') ? "Selected": '';?>>Efectivo</option>
                                <option value='tarjeta' <?php echo ( isset($ventas) && $ventas->metodoPago == 'tarjeta') ? "Selected": '';?>>Tarjeta</option>
                                <option value='cheque' <?php echo ( isset($ventas) && $ventas->metodoPago == 'cheque') ? "Selected": '';?>>Cheque</option>
                                <option value='Efectivo-Cheque' <?php echo ( isset($ventas) && $ventas->metodoPago == 'Efectivo-Cheque') ? "Selected": '';?>>Efectivo y cheque</option>
                                <option value='Efectivo-Tarjeta' <?php echo ( isset($ventas) && $ventas->metodoPago == 'Efectivo-Tarjeta') ? "Selected": '';?>>Efectivo y tarjeta</option>
	                        </select>
                        <?php //}?>
                    </div>

                    <div class="form-group" id="div_efectivo" style="display:none">
                    <label for="recibido_efectivo">Efectivo</label>
                        <div class="input-group" id="div_efectivo">
                            <div class="input-group-addon">$</div>
                            <input type="text" name="recibido_efectivo" id="recibido_efectivo" value="<?php echo $ventas->pago_efectivo;?>"  class="form-control "
								<?php //echo ($ventas->metodoPago != '') ? 'readonly="readonly"': '' ?>
                            >
                        </div>
                    </div>

                    <div id="div_tarjeta" style="display:none">
                        <div class="form-group">
                            <label for="tarjeta">Tarjeta</label>
                                <?php //if ($ventas->metodoPago != ''){?>    
                                <!-- <input type="text" name="tarjeta" id="tarjeta" value="<?php echo strtoupper($ventas->tarjeta);?>"  class="form-control " readonly="readonly"> -->
                                <?php //}else{ ?>
                                    <select name="tarjeta" id="tarjeta" class="form-control " >
                                        <option value="">Seleccione tarjeta</option>
                                        <option value="Visa">Visa</option>
                                        <option value="Mastercard">Mastercard</option>
                                        <option value="Dinners">Dinners</option>
                                        <option value="Nevada">Nevada</option>
                                        <option value="Cabal">Cabal</option>
                                        

                                    </select>
                                <?php //}?>
                        </div>
                        <div class="form-group" id="tarjeta"  >
                            <div class="input-group" >
                                <div class="input-group-addon">$</div>
                                <label for="recibido_tarjeta"></label>
                                <input type="text" name="recibido_tarjeta" id="recibido_tarjeta" value="<?php echo $ventas->pago_tarjeta;?>" class="form-control " 
                                <?php //echo ($ventas->metodoPago != '') ? 'readonly="readonly"': '' ?>
                                >
                            </div>
                        </div>
                    </div>

                    <div id="div_cheque" style="display:none">
                        <div class="form-group">
                            <label for="cheque">Cheque</label>
                        	    <?php //if ($ventas->metodoPago != ''){?>
                                    <!-- <div class="input-group" >
                                        <div class="input-group-addon">$</div>
                                        <label for="recibido_cheque"></label>
                                            <input type="text" name="recibido_cheque" id="recibido_cheque" value="<?php echo strtoupper($ventas->pago_cheque);?>" 
                                            <?php echo ($ventas->metodoPago != '') ? 'readonly="readonly"': '' ?> placeholder="Recibido cheque" class="form-control ">
                                    </div>    
                                    <label for="cheque_numero">Cheque Numero:</label> 
                                        <input type="text" name="cheque_numero" id="cheque_numero" value="<?php echo strtoupper($ventas->pago_cheque_numero);?>"  class="form-control " readonly="readonly">
                                    <label for="cheque_banco">Banco:</label>    
                                        <input type="text" name="cheque_banco" id="cheque_banco" value="<?php echo strtoupper($ventas->pago_cheque_banco);?>"  class="form-control " readonly="readonly">
                                    <label for="cheque_cuenta">Cuenta:</label>    
                                        <input type="text" name="cheque_cuenta" id="cheque_cuenta" value="<?php echo strtoupper($ventas->pago_cheque_cuenta);?>"  class="form-control " readonly="readonly"> -->
                        	    <?php //}else{ ?>
                                    <div class="input-group" >
                                        <div class="input-group-addon">$</div>
                                        <label for="recibido_cheque"></label>
                                            <input type="text" name="recibido_cheque" id="recibido_cheque"  placeholder="Recibido cheque" class="form-control ">
                                    </div>
                                    <label for="cheque_cuenta">Cheque Numero:</label>
                                        <input type="text" name="cheque_numero" id="cheque_numero" value=""  class="form-control">
                                    <label for="cheque_cuenta">Banco:</label>
                                        <input type="text" name="cheque_banco" id="cheque_banco" value=""  class="form-control ">
                                    <label for="cheque_cuenta">Cuenta:</label>
                                        <input type="text" name="cheque_cuenta" id="cheque_cuenta" value=""  class="form-control ">
                        	    <?php //}?>
                        </div>
                    
                    
                    </div>
                    <div class="text-center">
                        <button class="btn btn-warning " name="pago-venta" id="pago-venta" >Realizar cobro</button>
                    </div>
                </form>
            </div>

        </div>


        <script type="text/javascript">
$(document).ready(function() {
        var mp = $('#metodo_pago').val().toLowerCase(); 
        // alert(mp);
    	if(mp == 'efectivo'){
            $("#div_tarjeta").hide();
            $("#div_cheque").hide();
            $("#tarjeta").hide();
            $("#div_efectivo").show();    
        }
        if(mp == 'tarjeta'){
            $("#div_efectivo").hide();
            $("#div_cheque").hide();
            $("#div_tarjeta").show();
            $("#tarjeta").show();
        }
        if(mp == 'cheque'){
            $("#div_efectivo").hide();
            $("#div_tarjeta").hide();
        }

        if (mp == 'efectivo-tarjeta'){
            $("#div_efectivo").show();
            $("#div_tarjeta").show();
            $("#tarjeta").show();
            $("#div_cheque").hide();
        }

        if ( mp == 'efectivo-cheque'){
            $("#div_efectivo").show();
            $("#div_cheque").show();
            $("#tarjeta").hide();
            // $("#tarjeta").show();
            //$("#tarjeta").prop('disabled', true);
        }

        
        
    });

    $('#metodo_pago').on('change', function(){
        event.preventDefault();
        mp = $('#metodo_pago').val().toLowerCase();
        //alert(mp);
        if ( mp == 'tarjeta'  ){
            //alert(mp );
            $("#div_efectivo").hide();
            $("#div_cheque").hide();
            $("#recibido_efectivo").val('');
            $("#div_tarjeta").show();
            $("#tarjeta").show();
            $("#factura").show();
        }
        if ( mp == 'cheque'  ){
            //alert(mp );
            $("#div_efectivo").hide();
            $("#div_tarjeta").hide();
            $('#tarjeta').hide();
            $("#recibido_efectivo").val('');
            $("#div_cheque").show();
            $("#cheque").show();
            $("#factura").show();
        } 
        if ( mp == 'efectivo-tarjeta'){
            $("#div_efectivo").show();
            $("#div_tarjeta").show();
            $("#tarjeta").show();
            $("#factura").show();
            $("#div_cheque").hide();
            //$("#descuento").show();
            //$("#tarjeta").prop('disabled', true);
        }
        if ( mp == 'efectivo-cheque'){
            $("#div_cheque").show();
            $("#div_tarjeta").hide();
            $("#tarjeta").hide();
            $("#factura").show();
            $("#div_efectivo").show();
            //$("#descuento").show();
            //$("#tarjeta").prop('disabled', true);
        } 
        if (mp == 'efectivo'){
            $("#div_efectivo").show();
            $("#factura").show();
            $("#recibido_tarjeta").val('');
            $("#div_tarjeta").hide();
            $("#div_cheque").hide();
            $("#tarjeta").hide();
            //$("#descuento").show();
        } if (mp == 'ninguno') {
            $("#div_tarjeta").hide();
            $("#div_tarjeta").hide();
            $("#div_cheque").hide();
            $("#tarjeta").hide();
            $("#factura").hide();
            $("#div_efectivo").hide();
        }
    });

    $('#pago-venta').on('click', function() {
        //var estado = 'Aprobada';
        var idVenta = $('#id_venta').val();
        var total_importe = $("input[name=importe_total").val();
        var pago = $('#form_pago').serialize();

        var link = '<?= base_url() ?>ventas/';
        $.post(link + "pago_venta", {id: idVenta, importe: total_importe, pago: pago},
            function(data) {
                // alert(data); 
                // 
                if (data) {
                    alert('Pago actualizado');
                    
                    //location.href = link ;
                } else {
                   alert("El pago no actualizado!");
                 }
            });
        return false;
    });
</script>