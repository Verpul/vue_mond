<template>
  <div
    class="modal fade show d-block"
    id="todoModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="todoModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5
            class="modal-title"
            id="todoModalLabel"
            v-if="!modalData.editMode"
          >
            Создание задачи
          </h5>
          <h5 class="modal-title" id="todoModalLabel" v-else>
            Редактирование задачи
          </h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
            @click="$emit('close')"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <div class="modal-body">
              <div class="form-group">
                <label>Тема</label>
                <input
                  v-model="form.title"
                  type="text"
                  name="title"
                  class="form-control"
                  :class="{
                    'is-invalid': form.errors.has('title')
                  }"
                />
                <has-error :form="form" field="title"></has-error>
              </div>
              <div class="form-group">
                <label>Текст задачи</label>
                <textarea
                  v-model="form.task"
                  class="form-control"
                  rows="3"
                  :class="{
                    'is-invalid': form.errors.has('task')
                  }"
                ></textarea>
                <has-error :form="form" field="task"></has-error>
              </div>
              <div class="form-group">
                <label>Выполнить до (необязательно для заполнения)</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fas fa-user-clock"></i>
                    </div>
                  </div>
                  <date-picker
                    v-model="form.due_date"
                    :config="options"
                    v-mask="'99.99.9999 99:99'"
                  >
                  </date-picker>
                </div>
              </div>
              <div class="form-group">
                <label class="d-block mb-0">Прикрепить файлы</label>
                <small class="text-muted">
                  Только файлы следующих типов: .jpg .png .doc .docx .xls .xlsx
                  .pdf .zip .7z
                </small>
                <input
                  type="file"
                  class="form-control-file"
                  @change="attachFiles"
                  multiple
                />
                <div
                  :class="
                    fileListError
                      ? 'border border-danger is-invalid'
                      : 'border border-white'
                  "
                >
                  <ul class="list-group list-group-flush">
                    <li
                      class="list-group-item pt-1 pb-0 pl-1"
                      v-for="(file, index) in form.filesData.filesToSave"
                      :key="index"
                      :class="checkFileError(index) ? 'text-danger' : ''"
                    >
                      {{ file.name }}
                      <i
                        class="fas fa-times float-right text-danger pt-1"
                        style="cursor: pointer;"
                        @click="removeUnsavedFile(index)"
                      >
                      </i>
                    </li>
                  </ul>
                </div>
                <div class="help-block invalid-feedback">
                  Попытка сохранить файлы с запрещенными типами
                </div>
              </div>
              <div
                class="form-group mb-0"
                v-if="form.filesData.uploadedFiles.length !== 0"
              >
                <hr class="mt-1 mb-1" />
                <label>Сохраненные файлы:</label>
                <div class="border border-light">
                  <ul class="list-group list-group-flush">
                    <li
                      class="list-group-item pt-1 pb-0 pl-1 link"
                      v-for="(file, index) in form.filesData.uploadedFiles
                        .filenames"
                      :key="index"
                    >
                      <a href="" @click.prevent="downloadFile(file.name)"
                        >- {{ file.name }}</a
                      >
											<i
                        class="fas fa-times float-right text-danger pt-1"
                        style="cursor: pointer;"
												@click="removeSavedFile(index)"
                      >
                      </i>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-primary"
                data-dismiss="modal"
                @click="$emit('close')"
              >
                Закрыть
              </button>
              <button
                type="submit"
                class="btn btn-success"
                @click.prevent="modalData.editMode ? editTodo() : createTodo()"
              >
                Сохранить
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import datePicker from "vue-bootstrap-datetimepicker";
import "pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css";
import { objectToFormData } from "object-to-formdata";

export default {
  name: "TodoModal",
  data() {
    return {
      options: {
        format: "DD.MM.YYYY HH:mm",
        locale: this.$moment.locale(),
        icons: {
          time: "far fa-clock",
          date: "far fa-calendar",
          up: "fas fa-arrow-up",
          down: "fas fa-arrow-down",
          previous: "fas fa-chevron-left",
          next: "fas fa-chevron-right",
          today: "fas fa-calendar-check",
          clear: "far fa-trash-alt",
          close: "far fa-times-circle"
        },
        showClose: true,
        showClear: true
      },
      form: new Form({
        id: "",
        title: "",
        task: "",
        due_date: null,
        filesData: {
          uploadedFiles: [],
          filesToSave: []
        }
      }),
      fileListError: false
    };
  },
  methods: {
    createTodo() {
      this.fileListError = false;
      //Форматируем дату для MySQL
      if (this.form.due_date !== null) {
        this.form.due_date = this.$moment(
          this.form.due_date,
          "DD.MM.YYYY HH:mm"
        ).format("YYYY/MM/DD HH:mm");
      }

      this.form
        .post("/api/todo", {
          transformRequest: [
            (data, headers) => {
              return objectToFormData(data);
            }
          ]
        })
        .then(() => {
          this.$emit("loadTodos");
          this.$emit("close");

          this.$swal({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            icon: "success",
            title: "Новая задача добавлена!"
          });
        });
    },
    editTodo() {
      //Форматируем дату для MySQL
      if (this.form.due_date !== null) {
        this.form.due_date = this.$moment(
          this.form.due_date,
          "DD.MM.YYYY HH:mm"
        ).format("YYYY/MM/DD HH:mm");
      }

      this.form
        .post("/api/todo/" + this.form.id, {
          transformRequest: [
            (data, headers) => {
              data["_method"] = "PUT";
              return objectToFormData(data);
            }
          ]
        })
        .then(() => {
          this.$emit("loadTodos");
          this.$emit("close");

          this.$swal({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            icon: "success",
            title: "Задача изменена!"
          });
        });
    },
    attachFiles(event) {
      let files = event.target.files;

      this.form.filesData.filesToSave = [];

      //Очищаем ошибки при загрузке новых файлов
      this.fileListError = false;
      for (let err in this.form.errors.errors) {
        if (/filesData/.test(err)) {
          delete this.form.errors.errors[err];
        }
      }

      for (var i = 0; i < files.length; i++) {
        this.form.filesData.filesToSave.push(files[i]);
      }
      event.target.value = null;
    },
    //Отображение ошибки в загрузке файлов из-за типа
    checkFileError(index) {
      if (this.form.errors.has("filesData.filesToSave." + index)) {
        this.fileListError = true;
        return true;
      }
      return false;
    },
    downloadFile(name) {
      let folder = this.form.filesData.uploadedFiles.path;
      axios
        .get("api/file/" + folder + "/" + name, {
          responseType: "blob"
        })
        .then(response => {
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement("a");
          link.href = url;
          link.setAttribute("download", name);
          document.body.appendChild(link);
          link.click();
        });
    },
    removeUnsavedFile(index) {
      this.form.filesData.filesToSave.splice(index, 1);
		},
		removeSavedFile(index){
			this.form.filesData.uploadedFiles.filenames.splice(index, 1);
		}
  },
  created() {
    this.form.clear();
    this.form.reset();

    //Если это редактирование - заполняем поля
    if (this.modalData.editMode) {
      this.form.fill(this.modalData.todo);
      //Если будет null, то push при выборе файлов не сработает
      this.form.filesData = {
        uploadedFiles: [],
        filesToSave: []
      };
      //Переводим из строки в дату для input type=date
      if (this.form.due_date) {
        this.form.due_date = new Date(this.modalData.todo.due_date);
      }
    }
  },
  components: {
    datePicker
  },
  props: ["modalData"]
};
</script>

<style scoped>
#todoModal {
  overflow-y: scroll;
}

input[type="file"] {
  color: transparent;
}

input[type="file"]:hover {
  color: transparent;
}
</style>
