<?php
$empresa = $_GET['empresa'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<title>Autocomplete e Tabela de Produtos</title>
	<style>
		.flex-container {
			display: flex;
			background: #f5f6f7;
			width: 860px;
			padding: 10px;
			border-radius: 7px;
			flex-wrap: wrap;
			margin-left: -7%;
		}
		.flex-item {
			padding: 10px;
			flex: 1;
		}
		#caixa_1 {
			width: 300px;
		}
		#caixa_2 {
			width: 550px;
		}
		table th{
			white-space: nowrap;
		}
		.autocomplete-item {
			cursor: pointer;
		}
		.ui-autocomplete {
			z-index: 9999 !important; /* Aumentar o z-index para garantir que o autocomplete fique visível */
			background: #fff !important;
		}
		.div-pagamento {
			display: none;
			position: fixed;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			background: #fff;
			border-radius: 7px;
			padding: 20px;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.9);
		}
	</style>
</head>
<body>
	<div class="flex-container">
		<br><br>
		<div id="caixa_1" class="flex-item">
			<label for="autocomplete">Buscar Produto:</label>
			<input type="text" id="autocomplete" style="font-family: 'Arial','FontAwesome'" placeholder="&#xF002; Buscar produto" class="form-control">
			<br>
			<form id="produtoForm">
				<input type="hidden" name="id" id="id">
				<div style="border-radius: 4px; border: 1px solid #ddd; padding: 10px; display: flex; background: #fff;">
					<label for="valor">Valor:</label>
					<input style="border: none; width: 150px;" type="text" name="valor" id="valor" readonly>
				</div>
				<br>
				<div style="display: flex;">
					<div style="width: 160px; border-radius: 4px; border: 1px solid #ddd; padding: 10px; display: flex; background: #fff;">
						<label for="quantidade">Quantidade:&nbsp;&nbsp;</label>
						<input style="border: none; width: 50px;" type="number" name="quantidade" id="quantidade" min="1" value="1">
					</div>
					<div>&nbsp;</div>
					<div>
						<label style="font-size: 19px; width: 120px; padding: 10px 5px; height: 47px;" class="btn btn-success" id="addProduto">Add Produto</label>
					</div>
				</div>
				<label for="img"></label>
				<div style="width: 100%; background: #fff; border-radius: 4px; height: 237px; border: 1px solid #ddd; padding: 20px; display: flex;">
					<img src="shopping-cart.png" onerror="this.onerror=null;this.src='produto-sem-imagem.png'" style="width: 100%;" name="img" id="img" readonly>
				</div>
			</form>
		</div>
		<div id="caixa_2" class="flex-item">
			<div style="width: 100%; height: 360px; overflow-y: scroll; border-radius: 7px; border: 1px solid #ddd; background: #fff;">
				<table id="tabela_valores" class="table" style="background: #fff;">
					<thead>
						<tr>
							<th>CÓDIGO</th>
							<th>PRODUTO</th>
							<th>VLR. UN.</th>
							<th>QTD</th>
							<th>VLR. TOTAL</th>
							<th></th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
			<br>
			<div style="border-radius: 7px; border: 1px solid #ddd; padding: 5px; width: 100%; height: 70px; background: #fff;">
				<span style="color: #808080;">Sub-Total</span><br>
				<center><b style="font-size: 18px; font-weight: 900;">R$: <span id="valor_somado">00,00</span></b></center>
			</div>
		</div>
		<div style="text-align: center; flex: 1;">
			<label class="btn btn-success btn-lg" id="fecharPedido">Fechar pedido <i class="fa fa-check"></i></label>
			<label class="btn btn-danger btn-lg" onclick="$('#carregador, #tela_black').hide();">Cancelar Venda <i class="fa fa-ban"></i></label>
		</div>
	</div>

	<div class="div-pagamento" id="divPagamento">
		<h2>Total a pagar: R$ <span id="totalPagar">00,00</span></h2>
		<label for="metodoPagamento">Método de Pagamento:</label>
		<select id="metodoPagamento" class="form-control">
			<option value="credito">Crédito</option>
			<option value="debito">Débito</option>
			<option value="pix">Pix</option>
			<option value="dinheiro">Dinheiro</option>
		</select>
        <div id="trocoDiv" style="display: none;">
            <label for="valorPago">Valor Pago:</label>
            <input type="text" id="valorPago" class="form-control">
            <label for="troco">Troco:</label>
            <input type="text" id="troco" class="form-control" readonly>
        </div>          
		<br>
		<div style="display: flex;">
			<label id="confirmarVenda" style="height: 34px; margin: 3px; padding: 7px;" class="btn btn-success btn-block">Confirmar Venda <i class="fa fa-check"></i></label>
			<label id="voltarVenda" style="height: 34px; margin: 3px; padding: 7px;" class="btn btn-danger btn-block">Voltar <i class="fa fa-reply"></i></label>
		</div>
	</div>
    <script>
        $(document).ready(function() {
            let produtos = []; // Para armazenar os produtos selecionados

            // Mostrar/esconder div de troco com base no método de pagamento
            $('#metodoPagamento').change(function() {
                if ($(this).val() === 'dinheiro') {
                    $('#trocoDiv').show();
                } else {
                    $('#trocoDiv').hide();
                    $('#valorPago').val('');
                    $('#troco').val('');
                }
            });

            // Calcular troco
            $('#valorPago').on('input', function() {
                let total = parseFloat($("#valor_somado").text().replace(',', '.'));
                let valorPago = parseFloat($(this).val().replace(',', '.'));
                if (!isNaN(total) && !isNaN(valorPago) && valorPago >= total) {
                    let troco = valorPago - total;
                    $('#troco').val(troco.toFixed(2).replace('.', ','));
                } else {
                    $('#troco').val('');
                }
            });

            // Autocomplete
            $("#autocomplete").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "search_product.php?empresa=<?php echo $empresa;?>",
                        type: "GET",
                        dataType: "json",
                        data: {
                            term: request.term
                        },
                        success: function(data) {
                            response($.map(data, function(item) {
                                return {
                                    label: item.nome + " - " + item.codigo,
                                    value: item.nome,
                                    data: item
                                };
                            }));
                        }
                    });
                },
                select: function(event, ui) {
                    let product = ui.item.data;

                    // Verifica se a quantidade do produto é zero
                    if (product.qtd <= 0) {
                        alert('Este produto está esgotado.');
                        $("#autocomplete").val(''); // Limpa o campo de busca
                        return; // Impede a seleção do produto
                    }

                    $("#id").val(product.codigo);
                    $("#valor").val(product.valor.replace('.', ','));
                    $("#quantidade").val(1);
                    $("#img").attr('src', product.img);
                },
                open: function() {
                    $(this).autocomplete('widget'); // Garantir que o z-index seja aplicado
                }
            });

            // Adicionar produto à tabela
            $("#addProduto").click(function() {
                let id = $("#id").val();
                let nome = $("#autocomplete").val();
                let valor = parseFloat($("#valor").val().replace(',', '.'));
                let quantidade = parseInt($("#quantidade").val());
                let img = $("#img").val();

                if (id && nome && valor && quantidade) {
                    // Verificar se o produto já está na tabela
                    let produtoExistente = produtos.find(produto => produto.id === id);
                    if (produtoExistente) {
                        // Atualizar quantidade e valor total do produto existente
                        produtoExistente.quantidade += quantidade;
                        produtoExistente.valorTotal += valor * quantidade;
                    } else {
                        // Adicionar novo produto à tabela
                        let produto = {
                            id: id,
                            nome: nome,
                            valor: valor,
                            quantidade: quantidade,
                            valorTotal: valor * quantidade,
                            img: img
                        };
                        produtos.push(produto);
                    }

                    atualizarTabela();
                    atualizarSubtotal();
                }
            });

            // Atualizar tabela
            function atualizarTabela() {
                let tbody = $("#tabela_valores tbody");
                tbody.empty();
                produtos.forEach((produto, index) => {
                    tbody.append(`
                        <tr>
                        <td>${produto.id}</td>
                        <td>${produto.nome}</td>
                        <td>R$ ${produto.valor.toFixed(2).replace('.', ',')}</td>
                        <td>${produto.quantidade}</td>
                        <td>R$ ${produto.valorTotal.toFixed(2).replace('.', ',')}</td>
                        <td><label  class="removerProduto btn btn-default" data-index="${index}"><i class="fa fa-trash"></i></label></td>
                        </tr>
                    `);
                });
            }

            // Atualizar subtotal
            function atualizarSubtotal() {
                let subtotal = produtos.reduce((acc, produto) => acc + produto.valorTotal, 0);
                $("#valor_somado").text(subtotal.toFixed(2).replace('.', ','));
            }

            // Remover produto da tabela
            $(document).on('click', '.removerProduto', function() {
                let index = $(this).data('index');
                produtos.splice(index, 1);
                atualizarTabela();
                atualizarSubtotal();
            });

            // Mostrar div de pagamento
            $("#fecharPedido").click(function() {
                let totalPagar = $("#valor_somado").text();
                $("#totalPagar").text(totalPagar);
                $("#divPagamento").show();
            });

            // Voltar para a venda
            $("#voltarVenda").click(function() {
                $("#divPagamento").hide();
                $('#valorPago').val('');
            });

            // Confirmar venda
            $("#confirmarVenda").click(function() {
                let metodoPagamento = $("#metodoPagamento").val(); // Assumindo que há um select para método de pagamento
                let total = parseFloat($("#valor_somado").text().replace(',', '.')); 
                var trocado   = $('#troco').val();   
                var valorPago = $('#valorPago').val();  

                $.ajax({
                    url: "update_stock.php?empresa=<?php echo $empresa; ?>&trocado="+trocado+"&valorPago="+valorPago,
                    type: "POST",
                    data: {
                        itens: JSON.stringify(produtos), // Converter itens em JSON string
                        metodoPagamento: metodoPagamento,
                        total: total
                    },
                    success: function(response) {
                        alert(response);
                        $('#carregador, #tela_black').hide();
                        // Limpar a tabela ou redirecionar conforme necessário

          // Limpar todos os campos e resetar variáveis
                        produtos = [];
                        atualizarTabela();
                        atualizarSubtotal();
                        $('#metodoPagamento').val('credito'); // Resetar para um valor padrão
                        $('#trocoDiv').hide();
                        $('#valorPago').val('');
                        $('#troco').val('');
                        $('#autocomplete').val('');
                        $('#id').val('');
                        $('#valor').val('');
                        $('#quantidade').val('');
                        $('#img').attr('src', 'shopping-cart.png');
                        $('#divPagamento').hide(); // Ocultar a div de pagamento


                    },
                    error: function(xhr, status, error) {
                        console.error("Erro ao confirmar a venda: " + error);
                    }
                });
            });
        });
    </script>
</body>
</html>
