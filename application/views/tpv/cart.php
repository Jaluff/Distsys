<?php if(!$this->cart->contents()):
    echo '';
else:
?>
 
<?php 
$attributes = array('class' => 'form', 'id' => 'form_compra');
$action = base_url() . "tpv/update_cart";
echo form_open($action, $attributes); ?>
<table class="table table-condenced table-bordered table-stripped table-hover" cellspacing="0" width="100%">
    <thead>
        <tr class="active">
            <th width="20%">Cantidad</th>
            <th width="30%">Descripcion</th>
            <th width="15%">Precio</th>
            <th width="15%">Descuento</th>
            <th width="20%">Importe</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; ?>
        <?php 
//var_dump($this->cart->contents());
        foreach($this->cart->contents() as $items): 
            $row_data = array(
                'type'  => 'hidden',
                'name'  => 'rowid[]',
                'id'    => 'rowid',
                'value' => $items['rowid'],
                'class' => ''
            );
        ?>
        <tr <?php if($i&1){ echo 'class="alt"'; }?>>
            <td>
                
                <div class="col-md-7 col-xs-12">
                    <?php echo form_input($row_data); ?>
                    <?php echo form_input(array('name' => 'qty[]', 'id' => 'qty[]', 'value' => $items['qty'], 'maxlength' => '3', 'size' => '5', 'class' => 'form-control input-sm cantidad')); ?>
                </div>
            </td>
            <td>
            	<div class="col-md-12 col-xs-12">
            		<?php echo $items['name']; ?>
            	</div>
            </td>
            <td>
            	<div class="col-md-12 col-xs-12">
            		<?php echo form_input(array('name'=>'precio[]', 'id'=>'precio[]', 'value'=>$this->cart->format_number($items['price']), 'size' => '5', 'class' => 'form-control input-sm precio')) ; ?>
            	</div>
            </td>
            <td>
                <div class="col-md-12 col-xs-12">   
                    <?php echo form_input(array('name'=>'descuento[]', 'id'=>'descuento[]', 'value'=>$items['discount'], 'size' => '3', 'class' => 'form-control input-sm precio text-left')) ; ?>
                </div>
            </td>
            <td>
            	<div class="col-md-12 col-xs-12">
            		$<?php echo number_format($items['subtotal'], 2, '.',''); ?>
            	</div>
            </td>
            <td>
            	<div class="col-md-12 col-xs-12">
            		<button type="button" class="btn btn-danger eliminar_item" value="<?= $items['rowid']?>"  ><span class='glyphicon glyphicon-trash'></span></button>
            	</div>
            </td>
        </tr>
         
        <?php $i++; ?>
        <?php endforeach; ?>
         
        <!-- <tr>
            <td</td>
            <td></td>
            <td><strong>Total</strong></td>
            <td>&euro;<?php echo $this->cart->format_number($this->cart->total()); ?></td>
        </tr> -->
    </tbody>
</table>
 <?php echo form_hidden('ajax', '1'); ?>
<p class='text-right'>
	<!-- <button type="submit" class="btn btn-warning "  >Actualizar</button>  -->
	
	<?php //echo form_submit('', 'Update your Cart'); 
	echo anchor('tpv/empty_cart', 'Anular compra', 'class="btn btn-danger"');
	// echo anchor('tpv/empty_cart', 'Guardar compra', 'class="btn btn-success"');
	// echo anchor('tpv/empty_cart', 'Registrar compra', 'class="btn btn-primary"');

	?></p>

<?php 
echo form_close(); 
endif;
?>

<script type="text/javascript">
$(document).ready(function() { 
	$('#total').text('$<?php echo number_format($this->cart->total(), 2 , '.',''); ?>');  
    $('#total_importe').val('$<?php echo number_format($this->cart->total(), 2 , '.',''); ?>');
	
    $('.eliminar_item').on('click', function() {
            idRow = $(this).val(); //alert(cliente + pago);
            //(idRow);
            var link = "<?php echo base_url(); ?>";
            $.post(link + "tpv/delete_cart_item", {
                    rowid: idRow,
                },
                function(data) {
                    // Interact with returned data
                    if (data == 'true') {
                        alert("Operacion realizada con exito!");
                        $.get(link + "tpv/show_cart", function(cart) { // Get the contents of the url cart/show_cart
                            $("#cart_content").html(cart); // Replace the information in the div #cart_content with the retrieved data
                            $("#precio_producto").val('0');
                            $("#cantidad_producto").val('0');
                        });
                    } else {
                        alert("Hubo algun problema , verifique");
                    }
            });
            return false; // Stop the browser of loading the page defined in the form "action" parameter.
        });


});



$(".cantidad").on('change',function(){
            var link = "<?php echo base_url(); ?>";
            dataString = $("#form_compra").serialize();
            
            $.ajax({
                type: "POST",
                //cache: false,
                url: '<?= base_url() ?>' + "tpv/update_cart",
                data: dataString,
                //cache: false,
                success: function(msg){
                    // reload the (updated) cart
                    $.get(link + "tpv/show_cart", function(cart){ // Get the contents of the url cart/show_cart  
                    $("#cart_content").html(cart); // Replace the cart
                    
                    });
                }
                
            });
            return false;            
        });

$(".precio").on('change',function(){

            var link = "<?php echo base_url(); ?>";
            //var newprecio =  event.target.defaultValue;
            
            dataString = $("#form_compra").serialize();
           //alert(dataString);
            $.ajax({
                type: "POST",
                //cache: false,
                url: '<?= base_url() ?>' + "tpv/update_cart",
                data: dataString,
                //cache: false,
                success: function(msg){
                    // reload the (updated) cart
                    $.get(link + "tpv/show_cart", function(cart){ // Get the contents of the url cart/show_cart  
                    $("#cart_content").html(cart); // Replace the cart
                   $(".precio").val('0');
                    });

        

                }
                
            });
            return false;            
        });


</script>