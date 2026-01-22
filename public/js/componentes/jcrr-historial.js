export class HistorialComentarios extends HTMLElement {
    constructor() {
        super();        
        this.comentarios = [];
        this.url = this.getAttribute('url') || ''; 
        this.urlGet = this.getAttribute('urlGet') || ''; 
        this.urlDelete = this.getAttribute('urlDelete') || '';
        this.urlUpdate = this.getAttribute('urlUpdate') || '';
        this.idModelo = document.getElementById('idRegistroEditar')?.value || null; 
        this.modeloGuardar = this.getAttribute('modeloGuardar') || ''; 
        this.soloModelo = this.getAttribute('soloModelo') || ''; 
        this.render();                
    }
         
    connectedCallback() {
        
        if (this.urlGet) {        
            this.loadComments();  // Llamar a la función para obtener los comentarios
        } else {
            console.warn("No se encontró la URL para obtener comentarios.");
        }

        const historialTab = document.getElementById('pills-historial-tab');
        if (historialTab) {         
            historialTab.addEventListener('show.bs.tab', () => {
                this.loadComments();
            });
        } else {
            console.log("No se encuentra el elemento #pills-historial-tab.");
        }

        // Delegación de eventos desde el contenedor de los comentarios
        this.querySelector('#timeline').addEventListener('click', (event) => {
            const deleteBtn = event.target.closest('.deleteBtn');
            const editBtn = event.target.closest('.editBtn');

            if (deleteBtn) {
                const index = parseInt(deleteBtn.getAttribute('data-index'), 10);
                if (!isNaN(index)) {
                    this.deleteComment(index);
                }
            }

            if (editBtn) {
                const index = parseInt(editBtn.getAttribute('data-index'), 10);
                if (!isNaN(index)) {
                    this.editComment(index);
                }
            }
        });
    }
    
    async loadComments() {
              
        if(this.soloModelo=='sinidmodelo'){
            this.idModelo = this.soloModelo;
        }else{
            this.idModelo = document.getElementById('idRegistroEditar')?.value || null;      
        }

        if (this.idModelo) {
            try {
                const response = await fetch(`${this.urlGet}?idRegistroEditar=${this.idModelo}`);
                const data = await response.json();
                
                if (response.ok && !data.error && data.datos) {
                    this.comentarios = data.datos.map(item => ({
                        idcambio: item.idcambio,
                        comentario: item.comentario,
                        fecha: item.fecha,
                        creacion: item.creacion,
                        ultimamodificacion: item.ultimamodificacion,
                        usercompleto: item.usercompleto ?? 'Usuario desconocido', 
                    }));
                                        
                }else {
                    console.warn('No hay comentarios disponibles:', data.mensaje);
                    this.comentarios = []; // Vaciar comentarios si no hay datos
                }
    
                this.renderComments();
            } catch (error) {
                console.error('Error al cargar los comentarios:', error);
                this.comentarios = []; // Vaciar comentarios en caso de error
            } finally {
                this.renderComments(); // Asegurar que siempre se renderiza la lista
            }
        } else {
            console.warn('No se encontró un ID de registro válido.');
            this.comentarios = []; // Vaciar comentarios si no hay un ID
            this.renderComments();
        }
    }

    render() {
        const currentDate = new Date().toISOString().split('T')[0];
        this.innerHTML = `
            <div class="mt-3">             
                
                <div class="add-comment">
                    <div class="row container_add_comment">
                        <div class="col-md-3">
                            <input type="date" class="form-control"  id="commentDate" value="${currentDate}">
                        </div>
                        <div class="col-md-12">
                            <textarea id="commentInput" placeholder="Escribe un comentario..." rows="2"></textarea>
                        </div>
                    </div>
                    <button id="addCommentBtn">Añadir Comentario</button>
                </div>
                
                <ul class="comentarios" id="timeline">                    
                </ul>
            </div>
            <style>${styles}</style>        
        `;

        this.querySelector('#addCommentBtn').addEventListener('click', () => this.addComment());
    }

    async addComment() {
            const commentInput = this.querySelector('#commentInput');
            const commentText = commentInput.value.trim();
            const commentDate = this.querySelector('#commentDate').value;
        
            if (!this.idModelo) {
                this.idModelo = document.getElementById('idRegistroEditar')?.value || null;
            }
        
            if (commentText !== '' && commentDate !== '' && this.idModelo) {
                try {
                    const formData = new URLSearchParams();
                    formData.append('comentario', commentText);
                    formData.append('fecha', commentDate);
                    formData.append('idModelo', this.idModelo);
                    formData.append('modelo', this.modeloGuardar);
        
                    const response = await fetch(this.url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: formData.toString()
                    });
        
                    const data = await response.json();
        
                    if (/* response.ok &&  */!data.error && data.comentarios) {
                                                                              
                        this.comentarios = data.comentarios.map(item => ({
                            idcambio: item.idcambio,
                            comentario: item.comentario,
                            fecha: item.fecha,
                            usercompleto: item.usercompleto,
                            ultimamodificacion: item.ultimamodificacion,
                            creacion: item.creacion
                        }));
                                          
        
                        this.renderComments(); 
        
                        commentInput.value = ''; 
                    } else {
                        console.error('Error en la respuesta del servidor:', data.mensaje);
                    }
                } catch (error) {
                    console.error('Error al enviar el comentario:', error);
                }
            } else {
                console.warn("No se puede enviar el comentario. Datos faltantes.");
            }
    }
            
    async deleteComment(index) {
        
        const confirmDelete = confirm("¿Estás seguro de que deseas eliminar este comentario?");

        if (!this.idModelo) {
            this.idModelo = document.getElementById('idRegistroEditar')?.value || null;
        }
        
        if (confirmDelete) {
            try {
                
                const commentId = this.comentarios[index].idcambio;                  
                
                const formData = new URLSearchParams();
                formData.append('idRegistroEliminar', commentId);  
                formData.append('modelo', this.modeloGuardar);  
                formData.append('idRegistroPadre', this.idModelo);

                const response = await fetch(this.urlDelete, {
                    method: 'POST',  
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: formData.toString()
                });

                const data = await response.json();

                if (response.ok && !data.error) {
                    
                    Swal.fire('Eliminado!', 'El comentario ha sido eliminado.', 'success');

                    
                    this.comentarios = data.comentarios;  
                    this.renderComments();  
                } else {
                    
                    Swal.fire('Error', data.mensaje || 'No se pudo eliminar el comentario.', 'error');
                }
            } catch (error) {
                console.error('Error al eliminar el comentario:', error);
                Swal.fire('Error', 'Hubo un problema al intentar eliminar el comentario.', 'error');
            }
        } else {
            console.log("Eliminación cancelada");
        }
    }   

    async editComment(index) {
        const commentItem = this.querySelector(`li:nth-child(${index + 1})`);
        const commentText = commentItem.querySelector('.comment-text').value.trim();
        const commentDate = commentItem.querySelector('.comment-date').value;
        const commentId = commentItem.querySelector('.comment-text').dataset.idcambio;
    
        if (commentText !== '' && commentDate !== '') {
            try {
                const formData = new URLSearchParams();
                formData.append('comentario', commentText);
                formData.append('fecha', commentDate);
                formData.append('idRegistroEditar', commentId);
                formData.append('idModelo', this.idModelo);
                formData.append('modelo', this.modeloGuardar);
    
                const response = await fetch(this.urlUpdate, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: formData.toString()
                });
    
                const data = await response.json();
    
                if (response.ok && !data.error && data.comentarios) {
                    this.comentarios = data.comentarios.map(item => ({
                       
                        idcambio: item.idcambio,
                        comentario: item.comentario,
                        fecha: item.fecha,
                        usercompleto: item.usercompleto,
                        ultimamodificacion: item.ultimamodificacion,
                        creacion: item.creacion                        
                    }));
    
                    this.renderComments(); 
                        
                    Swal.fire({
                        icon: 'success',
                        title: 'Comentario actualizado',
                        text: data.mensaje || 'El comentario se actualizó correctamente.',
                        timer: 2000,
                        showConfirmButton: false
                    });
    
                } else {
                    console.error('Error en la respuesta del servidor:', data.mensaje);
                                        
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.mensaje || 'Hubo un problema al actualizar el comentario.',
                    });
                }
            } catch (error) {
                console.error('Error al enviar el comentario:', error);
                                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al comunicarse con el servidor.',
                });
            }
        } else {
            console.warn("No se puede enviar el comentario. Datos faltantes.");
                        
            Swal.fire({
                icon: 'warning',
                title: 'Datos incompletos',
                text: 'Asegúrate de llenar todos los campos antes de guardar.',
            });
        }
    }    

    renderComments() {
        const commentsContainer = this.querySelector('#timeline');
        let commentsHTML = '';

        if (this.comentarios && this.comentarios.length > 0) {
            commentsHTML = this.comentarios.map((comment, index) => `
                <li>
                    <div class="row container_comment">
                        <div class="comment-header col-md-3">
                            <input type="date" class="comment-date form-control" value="${comment.fecha}" data-index="${index}">
                            <div class="actions">
                                <i class="fas fa-edit editBtn" data-index="${index}" title="Guardar cambios"></i>
                                <i class="fas fa-trash-alt deleteBtn" data-index="${index}" title="Eliminar"></i>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <textarea class="comment-text form-control" data-idcambio="${comment.idcambio}" data-index="${index}">${comment.comentario}</textarea>
                        </div>       
                        <div class="col-md-12 comment-meta">
                        <small>Creado: ${comment.creacion} por ${comment.usercompleto}</small> |
                        <small>Última modificación: ${comment.ultimamodificacion}</small>
                    </div>           
                    </div>
                </li>
            `).join(''); // Unir todo el HTML generado en una sola cadena
        } else {
            commentsHTML = '<p>No hay comentarios disponibles.</p>';
        }

        commentsContainer.innerHTML = commentsHTML; // Reemplaza todo el contenido de una sola vez
    }

    
        
}

const styles = `     
            .comentarios {
                margin-top: 2rem;
                border-radius: 12px;
                position: relative;
                padding-left: 25px;
            }

            .comentarios li {
                padding-bottom: 1.5rem;
                border-left: 1px solid #f28d1b;
                position: relative;
                padding-left: 20px;
                margin-left: 10px;
                list-style: none;
            }

            .comentarios li:last-child {
                border: 0;
                padding-bottom: 0;
            }

            .comentarios li::before {
                content: '';
                width: 15px;
                height: 15px;
                background: white;
                border: 1px solid #af6114;
                box-shadow: 3px 3px 0px #f28d1b;
                border-radius: 50%;
                position: absolute;
                left: -10px;
                top: 0;
            }

            .time {
                color: #2a2839;
                font-family: 'Poppins', sans-serif;
                font-weight: 500;
                font-size: 1rem;
            }

            p {
                color: #4f4f4f;
                font-family: sans-serif;
                line-height: 1.5;
                margin-top: 0.4rem;
            }

            .add-comment {                
                flex-direction: column;
                margin-bottom: 1rem;
            }

            .add-comment textarea {
                width: 100%;
                padding: 10px;
                border-radius: 5px;
                border: 1px solid #ccc;
                font-size: 1rem;
                margin-bottom: 10px;
                resize: vertical;
            }

            .add-comment button {
                padding: 5px 10px;
                background-color: #f28d1b;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            .add-comment button:hover {
                background-color: #af6114;
            }

            
            
            .comment-header {
                display: flex;
                justify-content: flex-start;
                align-items: center;
                gap: 10px;
            }

            .actions {
                display: flex;
                gap: 10px;
            }

            .actions i {
                cursor: pointer;
                font-size: 16px;
                color: #555;
                transition: color 0.2s;
            }

            .actions i:hover {
                color: #007bff;
            }

            .deleteBtn, .editBtn {                                              
                cursor: pointer;
            }

            .deleteBtn:hover, .editBtn:hover {
                color: #af6114;
            }

            .row.container_comment {            
                gap: 5px;
            }
            .row.container_add_comment {
                gap: 10px;
            }
            .comment-meta {
                color: #af6114;
                font-size: 0.9rem;
                font-style: italic;
                font-weight: 100;
            }

            `;


customElements.define("jcrr-historial", HistorialComentarios);



