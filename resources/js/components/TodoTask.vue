<template>
  <div>
    <draggable tag="ul" class="todo-list"
      v-model="todos.data" @start="drag=true" @end="drag=false" handle=".handle">
    <li v-for="todo in todos.data" :key="todo.id">
      <div class="row m-0">
        <div class="col col-md-auto p-0">
          <!-- drag handle -->
          <span class="handle" v-if="showIcons">
            <i class="fas fa-ellipsis-v"></i>
            <i class="fas fa-ellipsis-v"></i>
          </span>
          <!-- Show adn hide todo steps -->
          <span style="cursor: pointer" @click="changeStepsVisible(todo.id)" 
            v-if="todo.steps.length !== 0 && showIcons">
            <i class="text-purple" :class="showSteps(todo.id) ? 
              'fas fa-chevron-circle-down' : 'fas fa-chevron-circle-right'"></i>
          </span>
          <!-- checkbox -->
          <div class="icheck-primary d-inline ml-1" v-if="todo.active">
            <input type="checkbox" value="" 
              :name="'todo' + todo.id" 
              :id="'todoCheck' + todo.id"
              @click.prevent="completeTodo(todo.id)">
            <label :for="'todoCheck' + todo.id"></label>
          </div>
          <!-- Emphasis label -->
          <small class="badge text-white ml-0" :class="badgeColor(todo.due_date)" 
            v-if="todo.active && showIcons">
            <i class="far fa-clock"></i>
          </small>
          <small class="badge badge-dark" v-if="!todo.active && showIcons">
            <i class="far fa-clock"></i> Завершена 
          </small>
          <small v-if="showDueDate">{{formatDate(todo.due_date)}}</small> 
        </div>
        <div class="col">
          <!-- todo title -->
          <span class="text" :class="todo.active ? '' : 'text-muted'">{{ todo.title }}:</span>
          <!-- General tools such as edit or delete-->
          <div class="tools">
            <i class="fas fa-plus-circle text-primary" 
              @click="addStepModal(todo.id)"
              v-if="todo.active">
            </i>
            <i class="fas fa-edit text-orange" 
              @click="openEditForm(todo)"
              v-if="todo.active">
             </i>
            <i class="fas fa-trash" @click="deleteTodo(todo.id)"></i>
          </div>
          <!-- todo text -->
          <span class="text font-weight-light" :class="todo.active ? '' : 'text-muted'">
          {{ todo.task }}</span>
          <todo-step 
            :steps="todo.steps"
            :active="todo.active"
            @openEditStep='openEditStep'
            v-if="showSteps(todo.id) && todo.steps.length !== 0"></todo-step>
        </div>
      </div>
    </li>
    </draggable>
    <step-modal v-if="showStepModal"
      :stepModalData="stepModalData"
      @stepCreated="stepCreated"
      @closeStepModal="showStepModal = false">
    </step-modal>
  </div>
</template>

<script>
  import TodoStepModal from './TodoStepModal';
  import TodoStep from './TodoStep';
  import draggable from 'vuedraggable';

  export default{
    data(){
      return {
        showStepModal: false,
        todoId: '',
        showStepsId: [],
        stepModalData: {
          todoId: '',
          step: {},
          editMode: false
        },
        showIcons: true,
        showDueDate: true
      }
    },
    methods: {
      // Отображает сколько осталось времени до истечения срока
      formatDate(date){
        if(date){
          let newDate = this.$moment(date).format('DD.MM.YYYY HH:mm');
          let dueIn = this.$moment(date).from();
          return ' До '+ newDate+ ' (' + dueIn +  ')';
        }
        return ' Бессрочно';    
      },
      badgeColor(date){
        if(date){
          var dueDate = this.$moment(date);
          var dateNow = this.$moment();
          var hoursUntilDue = dueDate.diff(dateNow, 'hours');
          
          if(hoursUntilDue < 1){
            return ' red'
          }else if(hoursUntilDue <= 7){
            return 'orange'
          }else if(hoursUntilDue <= 24){
            return 'text-dark yellow'
          }else if(hoursUntilDue <= 168){
            return 'green'
          }else{
            return 'teal'
          }
        }
        return 'cyan';
      },
      completeTodo(id){
        this.$swal({
          title: 'Вы уверены?',
          text: "Задача будет завершена!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Да, завершить!',
          cancelButtonText: 'Отмена'
          }).then((result) => {
              if (result.value) {
                  //Send update request
                  axios.put('/api/todo/active/'+id).then(() => {
                      //reload data table
                      this.$emit('loadTodos');

                      this.$swal({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        icon: 'success',
                        title: 'Задача выполнена!'
                      });   
                  }).catch(() => {
                      this.$swal(
                          'Действие отменено',
                          'Что-то пошло не так',
                          'error'
                      )
                  });
              }       
        })
      },
      deleteTodo(id){
        this.$swal({
          title: 'Вы уверены?',
          text: "Задача будет удалена!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Да, удалить!',
          cancelButtonText: 'Отмена'
          }).then((result) => {
              if (result.value) {
                  //Send delete request
                  axios.delete('api/todo/' + id).then(() => {
                      //reload data table
                      this.$emit('loadTodos');

                      this.$swal({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        icon: 'success',
                        title: 'Задача удалена!'
                      });   
                  }).catch(() => {
                      this.$swal(
                          'Действие отменено',
                          'Что-то пошло не так',
                          'error'
                      )
                  });
              }       
        })
      },
      // Форма редактирования Todo
      openEditForm(todo){
        this.$emit('openEdit', todo);
      },
      // Форма редактирования шага выполнения
      openEditStep(step){
        this.stepModalData.editMode = true;
        this.stepModalData.step = step;
        this.stepModalData.todoId = '';
        this.showStepModal = true;
      },
      // Добавляем или убираем id задачи для отображения и скрытия шагов выполнения
      changeStepsVisible(id){
        var index = this.showStepsId.indexOf(id);
        if(index == -1){
          this.showStepsId.push(id);
        }else{
          this.showStepsId.splice(index, 1);
        }
      },
      // После создания шага, он должен отобразиться
      stepCreated(id){
        var index = this.showStepsId.indexOf(id);
        if(index == -1){
          this.showStepsId.push(id);
        }
      },
      // Показываем модальное окно добавления шагов выполнения
      addStepModal(id){
        this.stepModalData.editMode = false;
        this.stepModalData.step = {};
        this.stepModalData.todoId = id;
        this.showStepModal = true;
      },
      // Если значение в массиве, то показываем шаги выполнения
      showSteps(id){
        if(this.showStepsId.indexOf(id) >= 0){
          return true;
        }
        return false;
      }
    },
    created(){
      Fire.$on('changeIconsView', () => {
        this.showIcons = !this.showIcons;
      });
      Fire.$on('changeDueDateView', () => {
        this.showDueDate = !this.showDueDate;
      });
    },
    components: {
      'step-modal': TodoStepModal,
      'todo-step': TodoStep,
      draggable,
    },
    props: [
      'todos',
    ]
  }
</script>

<style>
  
</style>


