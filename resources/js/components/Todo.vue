<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-12 mt-3">
        <!-- TO DO List -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              <i class="ion ion-clipboard mr-1"></i>
              Задачи
            </h3>
            <button type="button" class="btn btn-primary float-right" @click="openCreate"><i class="fas fa-plus"></i> Добавить</button>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <todo-task 
              :todos="todos" 
              @loadTodos="loadTodos"
              @openEdit="openEdit"
              ></todo-task>  
          </div>
          <!-- /.card-body -->
          <div class="card-footer clearfix">
            <div class="card-tools">
              <pagination :data="todos" @pagination-change-page="paginate"
                class="pagination pagination-sm"></pagination>
            </div>
          </div>
        </div>
      </div>
    </div>
    <todo-modal v-if="showModal"
      :modalData="modalData"
      @close="showModal = false" 
      @loadTodos="loadTodos">
    </todo-modal>
  </div>
</template>

<script>
  import TodoModal from './TodoModal';
  import TodoTask from './TodoTask';

  export default {
      name: 'Todo',
      data(){
        return {
          showModal: false,
          todos: {},
          modalData: {
            editMode: false,
            todo: {},
          }
        }
      },
      methods: {
        loadTodos(){
          let loader = this.$loading.show({});

          axios.get('/api/todo')
          .then((response) => {
            this.todos = response.data;
            loader.hide();
          })
        },
        paginate(page = 1) {
          let loader = this.$loading.show({});

          axios.get('api/todo?page=' + page)
            .then((response) => {
              this.todos = response.data;
              loader.hide();
          });
        },
        openEdit(todo){
          this.modalData.todo = todo;
          this.modalData.editMode = true;
          this.showModal = true;
        },
        openCreate(){
          this.modalData.todo = {};
          this.modalData.editMode = false;
          this.showModal = true;
        }
      },  
      components: {
        'todo-modal': TodoModal,
        'todo-task': TodoTask
      },
      created() {
        this.loadTodos();
      }
  }
</script>

<style>
  @import '~admin-lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css';
</style>
