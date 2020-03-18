<template>
<div class="modal fade show d-block" id="todoModal" tabindex="-1" role="dialog" aria-labelledby="todoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="todoModalLabel">Добавление шагов выполнения</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"
				@click="$emit('closeStepModal')">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
				<form>
				  <div class="modal-body">
						<div class="form-group">
							<label>Текст</label>
						  <textarea v-model="form.step" class="form-control" rows="5"
						  :class="{ 'is-invalid': form.errors.has('step') }"></textarea>
						  <has-error :form="form" field="step"></has-error>
						</div>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal" 
						@click="$emit('closeStepModal')">Закрыть</button>
					<button type="submit" class="btn btn-success" @click.prevent="createStep">Сохранить</button>
				  </div>
			  </form>
		  </div>
		</div>
  </div>
</div>
</template>

<script>
	export default {
		name: 'TodoStepModal',
		data(){
			return {
				form: new Form({
					id: '',
					todo_id: '',
					step: ''
				})
			}
		},
		methods: {
			createStep(){
				this.form.post('/api/todo/step/' + this.todoId)
	      	.then(() => {
	        this.$emit('loadTodos');
	        this.$emit('closeStepModal');

	        this.$swal({
	            toast: true,
	            position: 'top-end',
	            showConfirmButton: false,
	            timer: 3000,
	            icon: 'success',
	            title: 'Новый шаг выполнения добавлен!'
	        });
	      })
			}
		},
		// Для передачи на сервер через post
		created() {
			this.form.todo_id = this.todoId;
		},
		props: [
			'todoId'
		]
	}
</script>