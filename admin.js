// Admin JavaScript

// Variáveis globais
let currentSection = 'dashboard';
let tipoFuncionarioAtual = 'funcionario';

// Inicialização
document.addEventListener('DOMContentLoaded', function() {
    carregarDashboard();
    configurarNavegacao();
});

// Navegação entre seções
function configurarNavegacao() {
    const navButtons = document.querySelectorAll('.admin-nav-btn');
    
    navButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const section = this.getAttribute('data-section');
            trocarSecao(section);
        });
    });
}

function trocarSecao(section) {
    // Remover active de todos os botões e seções
    document.querySelectorAll('.admin-nav-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.admin-section').forEach(sec => sec.classList.remove('active'));
    
    // Adicionar active no botão e seção atual
    document.querySelector(`[data-section="${section}"]`).classList.add('active');
    document.getElementById(`${section}-section`).classList.add('active');
    
    currentSection = section;
    
    // Carregar dados da seção
    carregarDadosSecao(section);
}

function carregarDadosSecao(section) {
    switch(section) {
        case 'dashboard':
            carregarDashboard();
            break;
        case 'produtos':
            carregarProdutos();
            break;
        case 'pedidos':
            carregarPedidos();
            break;
        case 'clientes':
            carregarClientes();
            break;
        case 'funcionarios':
            carregarFuncionarios(tipoFuncionarioAtual);
            break;
        case 'relatorios':
            carregarRelatorios();
            break;
        case 'configuracoes':
            carregarConfiguracoes();
            break;
    }
}

// ===== DASHBOARD =====
async function carregarDashboard() {
    try {
        const response = await fetch('api_relatorios.php?acao=dashboard');
        const data = await response.json();
        
        if (data.success) {
            const d = data.dashboard;
            document.getElementById('total-clientes').textContent = d.total_clientes;
            document.getElementById('total-produtos').textContent = d.total_produtos;
            document.getElementById('pedidos-hoje').textContent = d.pedidos_hoje;
            document.getElementById('vendas-hoje').textContent = formatarMoeda(d.valor_vendas_hoje);
            document.getElementById('estoque-baixo').textContent = d.produtos_estoque_baixo;
            document.getElementById('pedidos-pendentes').textContent = d.pedidos_pendentes;
        }
    } catch (error) {
        console.error('Erro ao carregar dashboard:', error);
    }
}

// ===== PRODUTOS =====
async function carregarProdutos() {
    try {
        const response = await fetch('api_produtos.php?acao=listar');
        const data = await response.json();
        
        if (data.success) {
            renderizarProdutos(data.produtos);
        }
    } catch (error) {
        console.error('Erro ao carregar produtos:', error);
    }
}

function renderizarProdutos(produtos) {
    const tbody = document.getElementById('produtos-tbody');
    tbody.innerHTML = '';
    
    produtos.forEach(produto => {
        const status = produto.esgotado == 1 ? 
            '<span class="badge status-esgotado">Esgotado</span>' : 
            '<span class="badge status-disponivel">Disponível</span>';
        
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${produto.id}</td>
            <td>${produto.nome}</td>
            <td>${produto.categoria || '-'}</td>
            <td>${formatarMoeda(produto.preco)}</td>
            <td>${produto.estoque}</td>
            <td>${status}</td>
            <td>
                <button class="btn btn-sm btn-primary" onclick="editarProduto(${produto.id})">
                    <i class="fas fa-edit"></i> Editar
                </button>
                <button class="btn btn-sm btn-danger" onclick="deletarProduto(${produto.id})">
                    <i class="fas fa-trash"></i> Deletar
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

function abrirModalProduto(id = null) {
    const modal = document.getElementById('modal-produto');
    const titulo = document.getElementById('modal-produto-titulo');
    
    if (id) {
        titulo.textContent = 'Editar Produto';
        carregarDadosProduto(id);
    } else {
        titulo.textContent = 'Novo Produto';
        document.getElementById('form-produto').reset();
        document.getElementById('produto-id').value = '';
    }
    
    modal.classList.add('active');
}

async function carregarDadosProduto(id) {
    try {
        const response = await fetch(`api_produtos.php?acao=obter&id=${id}`);
        const data = await response.json();
        
        if (data.success) {
            const p = data.produto;
            document.getElementById('produto-id').value = p.id;
            document.getElementById('produto-nome').value = p.nome;
            document.getElementById('produto-descricao').value = p.descricao || '';
            document.getElementById('produto-preco').value = p.preco;
            document.getElementById('produto-estoque').value = p.estoque;
            document.getElementById('produto-categoria').value = p.categoria || '';
            document.getElementById('produto-imagem').value = p.imagem_url || '';
        }
    } catch (error) {
        console.error('Erro ao carregar produto:', error);
    }
}

async function salvarProduto(event) {
    event.preventDefault();
    
    const id = document.getElementById('produto-id').value;
    const produto = {
        id: id || undefined,
        nome: document.getElementById('produto-nome').value,
        descricao: document.getElementById('produto-descricao').value,
        preco: parseFloat(document.getElementById('produto-preco').value),
        estoque: parseInt(document.getElementById('produto-estoque').value),
        categoria: document.getElementById('produto-categoria').value,
        imagem_url: document.getElementById('produto-imagem').value
    };
    
    const acao = id ? 'editar' : 'criar';
    
    try {
        const response = await fetch(`api_produtos.php?acao=${acao}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(produto)
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            fecharModal('modal-produto');
            carregarProdutos();
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Erro ao salvar produto:', error);
        alert('Erro ao salvar produto');
    }
}

function editarProduto(id) {
    abrirModalProduto(id);
}

async function deletarProduto(id) {
    if (!confirm('Tem certeza que deseja deletar este produto?')) return;
    
    try {
        const response = await fetch('api_produtos.php?acao=deletar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            carregarProdutos();
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Erro ao deletar produto:', error);
        alert('Erro ao deletar produto');
    }
}

// ===== PEDIDOS =====
async function carregarPedidos() {
    try {
        const response = await fetch('api_pedidos.php?acao=listar');
        const data = await response.json();
        
        if (data.success) {
            renderizarPedidos(data.pedidos);
        }
    } catch (error) {
        console.error('Erro ao carregar pedidos:', error);
    }
}

function renderizarPedidos(pedidos) {
    const tbody = document.getElementById('pedidos-tbody');
    tbody.innerHTML = '';
    
    pedidos.forEach(pedido => {
        const statusClass = `status-${pedido.status}`;
        const pagamentoClass = `status-${pedido.status_pagamento}`;
        
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${pedido.id}</td>
            <td>${formatarData(pedido.data_pedido)}</td>
            <td>${pedido.cliente_nome || 'N/A'}</td>
            <td>${formatarMoeda(pedido.valor_total)}</td>
            <td><span class="badge ${statusClass}">${traduzirStatus(pedido.status)}</span></td>
            <td><span class="badge ${pagamentoClass}">${traduzirStatus(pedido.status_pagamento)}</span></td>
            <td>
                <button class="btn btn-sm btn-primary" onclick="verDetalhesPedido(${pedido.id})">
                    <i class="fas fa-eye"></i> Ver Detalhes
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

async function verDetalhesPedido(id) {
    try {
        const response = await fetch(`api_pedidos.php?acao=detalhes&id=${id}`);
        const data = await response.json();
        
        if (data.success) {
            renderizarDetalhesPedido(data.pedido);
            document.getElementById('pedido-id-display').textContent = id;
            document.getElementById('modal-pedido').classList.add('active');
        }
    } catch (error) {
        console.error('Erro ao carregar detalhes do pedido:', error);
    }
}

function renderizarDetalhesPedido(pedido) {
    const content = document.getElementById('pedido-detalhes-content');
    
    let itensHtml = '<h3>Itens do Pedido</h3><table class="admin-table"><thead><tr><th>Produto</th><th>Quantidade</th><th>Preço Unit.</th><th>Subtotal</th></tr></thead><tbody>';
    pedido.itens.forEach(item => {
        const subtotal = item.quantidade * item.preco_unitario;
        itensHtml += `<tr>
            <td>${item.produto_nome}</td>
            <td>${item.quantidade}</td>
            <td>${formatarMoeda(item.preco_unitario)}</td>
            <td>${formatarMoeda(subtotal)}</td>
        </tr>`;
    });
    itensHtml += '</tbody></table>';
    
    let historicoHtml = '<h3>Histórico de Status</h3><table class="admin-table"><thead><tr><th>Data</th><th>Status Anterior</th><th>Novo Status</th><th>Alterado Por</th></tr></thead><tbody>';
    pedido.historico.forEach(hist => {
        historicoHtml += `<tr>
            <td>${formatarData(hist.data_alteracao)}</td>
            <td>${traduzirStatus(hist.status_anterior)}</td>
            <td>${traduzirStatus(hist.status_novo)}</td>
            <td>${hist.alterado_por_nome}</td>
        </tr>`;
    });
    historicoHtml += '</tbody></table>';
    
    content.innerHTML = `
        <div class="pedido-info">
            <h3>Informações do Pedido</h3>
            <p><strong>Data:</strong> ${formatarData(pedido.data_pedido)}</p>
            <p><strong>Status:</strong> <span class="badge status-${pedido.status}">${traduzirStatus(pedido.status)}</span></p>
            <p><strong>Valor Total:</strong> ${formatarMoeda(pedido.valor_total)}</p>
            <p><strong>Método de Pagamento:</strong> ${traduzirMetodoPagamento(pedido.metodo_pagamento)}</p>
            <p><strong>Status Pagamento:</strong> <span class="badge status-${pedido.status_pagamento}">${traduzirStatus(pedido.status_pagamento)}</span></p>
            ${pedido.parcelas > 1 ? `<p><strong>Parcelas:</strong> ${pedido.parcelas}x</p>` : ''}
        </div>
        
        <div class="cliente-info">
            <h3>Informações do Cliente</h3>
            <p><strong>Nome:</strong> ${pedido.cliente_nome}</p>
            <p><strong>Email:</strong> ${pedido.cliente_email}</p>
            <p><strong>Celular:</strong> ${pedido.cliente_celular || 'N/A'}</p>
            ${pedido.cep ? `
                <p><strong>Endereço:</strong> ${pedido.rua}, ${pedido.numero} - ${pedido.bairro}</p>
                <p><strong>Cidade/Estado:</strong> ${pedido.cidade}/${pedido.estado}</p>
                <p><strong>CEP:</strong> ${pedido.cep}</p>
            ` : ''}
        </div>
        
        ${pedido.entregador_nome ? `
            <div class="entregador-info">
                <h3>Informações do Entregador</h3>
                <p><strong>Nome:</strong> ${pedido.entregador_nome}</p>
                <p><strong>Veículo:</strong> ${pedido.entregador_veiculo}</p>
            </div>
        ` : ''}
        
        ${itensHtml}
        ${historicoHtml}
        
        <div class="pedido-acoes" style="margin-top: 2rem;">
            <h3>Ações</h3>
            <select id="novo-status-pedido" class="form-control" style="margin-bottom: 1rem; padding: 0.5rem;">
                <option value="novo" ${pedido.status === 'novo' ? 'selected' : ''}>Novo</option>
                <option value="em_preparacao" ${pedido.status === 'em_preparacao' ? 'selected' : ''}>Em Preparação</option>
                <option value="enviado" ${pedido.status === 'enviado' ? 'selected' : ''}>Enviado</option>
                <option value="entregue" ${pedido.status === 'entregue' ? 'selected' : ''}>Entregue</option>
                <option value="cancelado" ${pedido.status === 'cancelado' ? 'selected' : ''}>Cancelado</option>
            </select>
            <button class="btn btn-primary" onclick="atualizarStatusPedido(${pedido.id})">
                <i class="fas fa-save"></i> Atualizar Status
            </button>
            <button class="btn btn-success" onclick="enviarNotaFiscal(${pedido.id})" ${pedido.nota_fiscal_enviada ? 'disabled' : ''}>
                <i class="fas fa-file-invoice"></i> ${pedido.nota_fiscal_enviada ? 'Nota Fiscal Enviada' : 'Enviar Nota Fiscal'}
            </button>
        </div>
    `;
}

async function atualizarStatusPedido(pedidoId) {
    const novoStatus = document.getElementById('novo-status-pedido').value;
    
    try {
        const response = await fetch('api_pedidos.php?acao=atualizar_status', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ pedido_id: pedidoId, status: novoStatus })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            fecharModal('modal-pedido');
            carregarPedidos();
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Erro ao atualizar status:', error);
        alert('Erro ao atualizar status');
    }
}

async function enviarNotaFiscal(pedidoId) {
    if (!confirm('Deseja enviar a nota fiscal para o cliente?')) return;
    
    try {
        const response = await fetch('api_pedidos.php?acao=enviar_nota_fiscal', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ pedido_id: pedidoId })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            fecharModal('modal-pedido');
            carregarPedidos();
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Erro ao enviar nota fiscal:', error);
        alert('Erro ao enviar nota fiscal');
    }
}

// ===== CLIENTES =====
async function carregarClientes() {
    try {
        const response = await fetch('api_clientes.php?acao=listar');
        const data = await response.json();
        
        if (data.success) {
            renderizarClientes(data.clientes);
        }
    } catch (error) {
        console.error('Erro ao carregar clientes:', error);
    }
}

function renderizarClientes(clientes) {
    const tbody = document.getElementById('clientes-tbody');
    tbody.innerHTML = '';
    
    clientes.forEach(cliente => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${cliente.id}</td>
            <td>${cliente.nome}</td>
            <td>${cliente.email}</td>
            <td>${cliente.celular || 'N/A'}</td>
            <td>${formatarData(cliente.data_cadastro)}</td>
            <td>${formatarMoeda(cliente.valor_total_compras)}</td>
            <td>
                <button class="btn btn-sm btn-primary" onclick="verDetalhesCliente(${cliente.id})">
                    <i class="fas fa-eye"></i> Ver Detalhes
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

async function verDetalhesCliente(id) {
    try {
        const response = await fetch(`api_clientes.php?acao=detalhes&id=${id}`);
        const data = await response.json();
        
        if (data.success) {
            renderizarDetalhesCliente(data.cliente);
            document.getElementById('modal-cliente').classList.add('active');
        }
    } catch (error) {
        console.error('Erro ao carregar detalhes do cliente:', error);
    }
}

function renderizarDetalhesCliente(cliente) {
    const content = document.getElementById('cliente-detalhes-content');
    
    let comprasHtml = '<h3>Histórico de Compras</h3><table class="admin-table"><thead><tr><th>ID</th><th>Data</th><th>Status</th><th>Valor</th></tr></thead><tbody>';
    cliente.historico_compras.forEach(compra => {
        comprasHtml += `<tr>
            <td>${compra.id}</td>
            <td>${formatarData(compra.data_pedido)}</td>
            <td><span class="badge status-${compra.status}">${traduzirStatus(compra.status)}</span></td>
            <td>${formatarMoeda(compra.valor_total)}</td>
        </tr>`;
    });
    comprasHtml += '</tbody></table>';
    
    content.innerHTML = `
        <div class="cliente-info">
            <h3>Informações Pessoais</h3>
            <p><strong>Nome:</strong> ${cliente.nome}</p>
            <p><strong>Email:</strong> ${cliente.email}</p>
            <p><strong>Celular:</strong> ${cliente.celular || 'N/A'}</p>
            <p><strong>Data de Nascimento:</strong> ${cliente.dataNascimento || 'N/A'}</p>
            <p><strong>Data de Cadastro:</strong> ${formatarData(cliente.data_cadastro)}</p>
            ${cliente.cep ? `
                <h3>Endereço</h3>
                <p><strong>CEP:</strong> ${cliente.cep}</p>
                <p><strong>Endereço:</strong> ${cliente.rua}, ${cliente.numero}</p>
                <p><strong>Bairro:</strong> ${cliente.bairro}</p>
                <p><strong>Cidade/Estado:</strong> ${cliente.cidade}/${cliente.estado}</p>
            ` : ''}
        </div>
        ${comprasHtml}
    `;
}

// ===== FUNCIONÁRIOS =====
function toggleTipoFuncionario(tipo) {
    tipoFuncionarioAtual = tipo;
    
    if (tipo === 'funcionario') {
        document.getElementById('funcionarios-container').style.display = 'block';
        document.getElementById('entregadores-container').style.display = 'none';
    } else {
        document.getElementById('funcionarios-container').style.display = 'none';
        document.getElementById('entregadores-container').style.display = 'block';
    }
    
    carregarFuncionarios(tipo);
}

async function carregarFuncionarios(tipo) {
    try {
        const response = await fetch(`api_funcionarios.php?acao=listar&tipo=${tipo}`);
        const data = await response.json();
        
        if (data.success) {
            renderizarFuncionarios(data.funcionarios, tipo);
        }
    } catch (error) {
        console.error('Erro ao carregar funcionários:', error);
    }
}

function renderizarFuncionarios(funcionarios, tipo) {
    const tbodyId = tipo === 'entregador' ? 'entregadores-tbody' : 'funcionarios-tbody';
    const tbody = document.getElementById(tbodyId);
    tbody.innerHTML = '';
    
    funcionarios.forEach(func => {
        const status = func.ativo == 1 ? 
            '<span class="badge status-ativo">Ativo</span>' : 
            '<span class="badge status-inativo">Inativo</span>';
        
        const campo = tipo === 'entregador' ? func.veiculo : func.funcao;
        
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${func.id}</td>
            <td>${func.nome}</td>
            <td>${func.email}</td>
            <td>${func.celular}</td>
            <td>${campo}</td>
            <td>${status}</td>
            <td>
                <button class="btn btn-sm btn-primary" onclick="editarFuncionario(${func.id}, '${tipo}')">
                    <i class="fas fa-edit"></i> Editar
                </button>
                <button class="btn btn-sm btn-danger" onclick="deletarFuncionario(${func.id}, '${tipo}')">
                    <i class="fas fa-trash"></i> Deletar
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

function abrirModalFuncionario(tipo, id = null) {
    const modal = document.getElementById('modal-funcionario');
    const titulo = document.getElementById('modal-funcionario-titulo');
    
    document.getElementById('funcionario-tipo').value = tipo;
    
    if (tipo === 'entregador') {
        document.getElementById('funcionario-funcao-group').style.display = 'none';
        document.getElementById('funcionario-veiculo-group').style.display = 'block';
        titulo.textContent = id ? 'Editar Entregador' : 'Novo Entregador';
    } else {
        document.getElementById('funcionario-funcao-group').style.display = 'block';
        document.getElementById('funcionario-veiculo-group').style.display = 'none';
        titulo.textContent = id ? 'Editar Funcionário' : 'Novo Funcionário';
    }
    
    if (id) {
        carregarDadosFuncionario(id, tipo);
    } else {
        document.getElementById('form-funcionario').reset();
        document.getElementById('funcionario-id').value = '';
    }
    
    modal.classList.add('active');
}

async function carregarDadosFuncionario(id, tipo) {
    try {
        const response = await fetch(`api_funcionarios.php?acao=obter&id=${id}&tipo=${tipo}`);
        const data = await response.json();
        
        if (data.success) {
            const f = data.funcionario;
            document.getElementById('funcionario-id').value = f.id;
            document.getElementById('funcionario-nome').value = f.nome;
            document.getElementById('funcionario-email').value = f.email;
            document.getElementById('funcionario-celular').value = f.celular;
            document.getElementById('funcionario-cep').value = f.cep || '';
            document.getElementById('funcionario-rua').value = f.rua || '';
            document.getElementById('funcionario-numero').value = f.numero || '';
            document.getElementById('funcionario-bairro').value = f.bairro || '';
            document.getElementById('funcionario-cidade').value = f.cidade || '';
            document.getElementById('funcionario-estado').value = f.estado || '';
            
            if (tipo === 'entregador') {
                document.getElementById('funcionario-veiculo').value = f.veiculo;
            } else {
                document.getElementById('funcionario-funcao').value = f.funcao;
            }
        }
    } catch (error) {
        console.error('Erro ao carregar funcionário:', error);
    }
}

async function salvarFuncionario(event) {
    event.preventDefault();
    
    const id = document.getElementById('funcionario-id').value;
    const tipo = document.getElementById('funcionario-tipo').value;
    
    const funcionario = {
        id: id || undefined,
        nome: document.getElementById('funcionario-nome').value,
        email: document.getElementById('funcionario-email').value,
        celular: document.getElementById('funcionario-celular').value,
        cep: document.getElementById('funcionario-cep').value,
        rua: document.getElementById('funcionario-rua').value,
        numero: document.getElementById('funcionario-numero').value,
        bairro: document.getElementById('funcionario-bairro').value,
        cidade: document.getElementById('funcionario-cidade').value,
        estado: document.getElementById('funcionario-estado').value
    };
    
    if (tipo === 'entregador') {
        funcionario.veiculo = document.getElementById('funcionario-veiculo').value;
    } else {
        funcionario.funcao = document.getElementById('funcionario-funcao').value;
    }
    
    const acao = id ? 'editar' : 'criar';
    
    try {
        const response = await fetch(`api_funcionarios.php?acao=${acao}&tipo=${tipo}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(funcionario)
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            fecharModal('modal-funcionario');
            carregarFuncionarios(tipo);
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Erro ao salvar funcionário:', error);
        alert('Erro ao salvar funcionário');
    }
}

function editarFuncionario(id, tipo) {
    abrirModalFuncionario(tipo, id);
}

async function deletarFuncionario(id, tipo) {
    if (!confirm('Tem certeza que deseja deletar?')) return;
    
    try {
        const response = await fetch(`api_funcionarios.php?acao=deletar&tipo=${tipo}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            carregarFuncionarios(tipo);
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Erro ao deletar:', error);
        alert('Erro ao deletar');
    }
}

// ===== RELATÓRIOS =====
async function carregarRelatorios() {
    await carregarRelatorioVendas();
    await carregarProdutosMaisVendidos();
    await carregarEstoqueBaixo();
}

async function carregarRelatorioVendas() {
    const periodo = document.getElementById('periodo-vendas').value;
    
    try {
        const response = await fetch(`api_relatorios.php?acao=vendas&periodo=${periodo}`);
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('rel-total-vendas').textContent = formatarMoeda(data.totais.valor_total);
            document.getElementById('rel-total-pedidos').textContent = data.totais.total_pedidos;
            document.getElementById('rel-ticket-medio').textContent = formatarMoeda(data.totais.ticket_medio);
        }
    } catch (error) {
        console.error('Erro ao carregar relatório de vendas:', error);
    }
}

async function carregarProdutosMaisVendidos() {
    try {
        const response = await fetch('api_relatorios.php?acao=produtos_mais_vendidos&limite=10');
        const data = await response.json();
        
        if (data.success) {
            renderizarProdutosMaisVendidos(data.produtos);
        }
    } catch (error) {
        console.error('Erro ao carregar produtos mais vendidos:', error);
    }
}

function renderizarProdutosMaisVendidos(produtos) {
    const tbody = document.getElementById('produtos-mais-vendidos-tbody');
    tbody.innerHTML = '';
    
    produtos.forEach((produto, index) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${index + 1}º</td>
            <td>${produto.nome}</td>
            <td>${produto.categoria}</td>
            <td>${produto.total_vendido}</td>
            <td>${formatarMoeda(produto.receita_total)}</td>
        `;
        tbody.appendChild(tr);
    });
}

async function carregarEstoqueBaixo() {
    try {
        const response = await fetch('api_relatorios.php?acao=estoque_baixo&limite_estoque=10');
        const data = await response.json();
        
        if (data.success) {
            renderizarEstoqueBaixo(data.produtos);
        }
    } catch (error) {
        console.error('Erro ao carregar estoque baixo:', error);
    }
}

function renderizarEstoqueBaixo(produtos) {
    const tbody = document.getElementById('estoque-baixo-tbody');
    tbody.innerHTML = '';
    
    produtos.forEach(produto => {
        const status = produto.esgotado == 1 ? 
            '<span class="badge status-esgotado">Esgotado</span>' : 
            '<span class="badge status-warning">Estoque Baixo</span>';
        
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${produto.id}</td>
            <td>${produto.nome}</td>
            <td>${produto.categoria}</td>
            <td>${produto.estoque}</td>
            <td>${status}</td>
        `;
        tbody.appendChild(tr);
    });
}

// ===== CONFIGURAÇÕES =====
async function carregarConfiguracoes() {
    await carregarAdmins();
}

async function carregarAdmins() {
    try {
        const response = await fetch('api_config.php?acao=listar_admins');
        const data = await response.json();
        
        if (data.success) {
            renderizarAdmins(data.admins);
        }
    } catch (error) {
        console.error('Erro ao carregar administradores:', error);
    }
}

function renderizarAdmins(admins) {
    const tbody = document.getElementById('admins-tbody');
    tbody.innerHTML = '';
    
    admins.forEach(admin => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${admin.id}</td>
            <td>${admin.nome}</td>
            <td>${admin.email}</td>
            <td>${formatarData(admin.data_cadastro)}</td>
            <td>
                <button class="btn btn-sm btn-danger" onclick="removerAdmin(${admin.id})">
                    <i class="fas fa-trash"></i> Remover
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

function abrirModalAdmin() {
    document.getElementById('form-admin').reset();
    document.getElementById('modal-admin').classList.add('active');
}

async function salvarAdmin(event) {
    event.preventDefault();
    
    const admin = {
        nome: document.getElementById('admin-nome').value,
        email: document.getElementById('admin-email').value
    };
    
    try {
        const response = await fetch('api_config.php?acao=criar_admin', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(admin)
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            fecharModal('modal-admin');
            carregarAdmins();
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Erro ao criar administrador:', error);
        alert('Erro ao criar administrador');
    }
}

async function removerAdmin(id) {
    if (!confirm('Tem certeza que deseja remover este administrador?')) return;
    
    try {
        const response = await fetch('api_config.php?acao=remover_admin', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            carregarAdmins();
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Erro ao remover administrador:', error);
        alert('Erro ao remover administrador');
    }
}

// ===== FUNÇÕES AUXILIARES =====
function fecharModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

function formatarMoeda(valor) {
    return new Intl.NumberFormat('pt-BR', { 
        style: 'currency', 
        currency: 'BRL' 
    }).format(valor);
}

function formatarData(data) {
    if (!data) return 'N/A';
    const d = new Date(data);
    return d.toLocaleDateString('pt-BR') + ' ' + d.toLocaleTimeString('pt-BR');
}

function traduzirStatus(status) {
    const traducoes = {
        'novo': 'Novo',
        'em_preparacao': 'Em Preparação',
        'enviado': 'Enviado',
        'entregue': 'Entregue',
        'cancelado': 'Cancelado',
        'aprovado': 'Aprovado',
        'pendente': 'Pendente',
        'falhou': 'Falhou'
    };
    return traducoes[status] || status;
}

function traduzirMetodoPagamento(metodo) {
    const traducoes = {
        'cartao_debito': 'Cartão de Débito',
        'cartao_credito': 'Cartão de Crédito',
        'pagar_entrega': 'Pagar na Entrega'
    };
    return traducoes[metodo] || metodo;
}

// Fechar modal ao clicar fora
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.classList.remove('active');
    }
}
