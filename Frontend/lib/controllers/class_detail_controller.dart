import 'package:nega_lms/utils/imports.dart';

class ClassDetailController extends GetxController with GetSingleTickerProviderStateMixin {
  late TabController tabController;
  RxBool isLoading = true.obs;
  RxInt classId = 0.obs;
  RxString token = "".obs;
  RxString classCode = ''.obs;
  RxString className = ''.obs;
  RxString classDescription = ''.obs;
  RxString teacherName = ''.obs;
  RxString assignmentTitle = 'test'.obs;
  RxInt currentQuestion = 0.obs;

  RxList<AssignmentModel> assignmentList = <AssignmentModel>[].obs;
  Rx<AssignmentModel> assignment = AssignmentModel(
    id: 0,
    creatorName: '',
    name: '',
    description: '',
    duration: 0,
    startDate: '',
    dueDate: '',
  ).obs;

  RxList<QuestionModel> questionList = <QuestionModel>[].obs;
  RxList<AnswerModel> answerList = <AnswerModel>[].obs;
  RxString timeLeft = '00:00:00'.obs;

  RxString assignmentType = ''.obs;

  TextEditingController assignmentName = TextEditingController();
  TextEditingController assignmentSubject = TextEditingController();
  RxString assignmentStatus = ''.obs;
  RxString assignmentLevel = ''.obs;
  RxString assignmentTotalScore = ''.obs;
  RxString assignmentDuration = ''.obs;
  TextEditingController assignmentStartDate = TextEditingController();
  TextEditingController assignmentDueDate = TextEditingController();
  RxString assignmentSpecialized = ''.obs;
  RxString assignmentTopic = ''.obs;
  TextEditingController assignmentDescription = TextEditingController();
  RxList<Map<String, dynamic>> questions = <Map<String, dynamic>>[].obs;

  RxBool isAssignmentNameError = false.obs;
  RxBool isAssignmentSubjectError = false.obs;
  RxBool isAssignmentStatusError = false.obs;
  RxBool isAssignmentLevelError = false.obs;
  RxBool isAssignmentStartDateError = false.obs;
  RxBool isAssignmentDueDateError = false.obs;
  RxBool isAssignmentSpecializedError = false.obs;
  RxBool isAssignmentTopicError = false.obs;
  RxBool isAssignmentDescriptionError = false.obs;

  RxString assignmentNameError = ''.obs;
  RxString assignmentSubjectError = ''.obs;
  RxString assignmentStatusError = ''.obs;
  RxString assignmentLevelError = ''.obs;
  RxString assignmentStartDateError = ''.obs;
  RxString assignmentDueDateError = ''.obs;
  RxString assignmentSpecializedError = ''.obs;
  RxString assignmentTopicError = ''.obs;
  RxString assignmentDescriptionError = ''.obs;

  @override
  void onInit() {
    super.onInit();
    tabController = TabController(length: 3, vsync: this);
    WidgetsBinding.instance.addPostFrameCallback((_) async {
      if (!await Token.checkToken()) return;
      token.value = await Token.getToken();
      classId.value = Get.arguments ?? 1; // remove this line after finish testing
      await fetchClassInfo(classId.value);
      await fetchClassAssignment(classId.value);
    });
    addNewQuestion();
  }

  fetchClassInfo(id) async {
    try {
      String url = "${Api.server}classes/$id";
      var response = await get(Uri.parse(url), headers: {
        'Authorization': 'Bearer $token',
      }).timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        var data = jsonDecode(response.body);
        classCode.value = data['code'];
        className.value = data['name'];
        classDescription.value = data['description'];
        teacherName.value = data['teacherName'];
      }
    } catch (e) {
      Get.snackbar("Error", "Failed to fetch class info");
    }
  }

  fetchClassAssignment(id) async {
    try {
      isLoading(true);
      String url = "${Api.server}assignment/$id";
      var response = await get(Uri.parse(url), headers: {
        'Authorization': 'Bearer $token',
      }).timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        var data = jsonDecode(response.body);
        var assignmentData = data['assignments'];
        assignmentList.value = (assignmentData as List).map((e) => AssignmentModel.fromMap(e)).toList();
      }
    } finally {
      isLoading(false);
    }
  }

  void addNewQuestion() {
    questions.add({
      'question': TextEditingController(),
      'score': TextEditingController(text: '1'), // Default 1 point
      'answers': <Map<String, dynamic>>[].obs,
    });
  }

  void addAnswerToQuestion(int questionIndex) {
    var currentAnswers = questions[questionIndex]['answers'] as RxList<Map<String, dynamic>>;
    currentAnswers.add({
      'controller': TextEditingController(),
      'isCorrect': false.obs,
    });
    questions.refresh();
  }

  void toggleAnswerCorrect(int questionIndex, int answerIndex) {
    var answers = questions[questionIndex]['answers'] as RxList<Map<String, dynamic>>;
    var currentValue = answers[answerIndex]['isCorrect'] as RxBool;
    currentValue.toggle();
    questions.refresh();
  }

  bool validateQuiz() {
    RxBool error = false.obs;
    if (assignmentName.text.trim().isEmpty) {
      error.value = true;
      isAssignmentNameError = true.obs;
      assignmentNameError.value = "Tên bài tập không được để trống";
    }

    if (assignmentSubject.text.trim().isEmpty) {
      error.value = true;
      isAssignmentSubjectError = true.obs;
      assignmentSubjectError.value = "Môn học không được để trống";
    }

    if (assignmentStatus.value.isEmpty) {
      error.value = true;
      isAssignmentStatusError = true.obs;
      assignmentStatusError.value = "Trạng thái không được để trống";
    }

    if (assignmentLevel.value.isEmpty) {
      error.value = true;
      isAssignmentLevelError = true.obs;
      assignmentLevelError.value = "Cấp độ không được để trống";
    }

    if (assignmentStartDate.text.trim().isEmpty) {
      error.value = true;
      isAssignmentStartDateError = true.obs;
      assignmentStartDateError.value = "Ngày bắt đầu không được để trống";
    }

    if (assignmentDueDate.text.trim().isEmpty) {
      error.value = true;
      isAssignmentDueDateError = true.obs;
      assignmentDueDateError.value = "Ngày kết thúc không được để trống";
    }

    if (assignmentSpecialized.value.isEmpty) {
      error.value = true;
      isAssignmentSpecializedError = true.obs;
      assignmentSpecializedError.value = "Chuyên ngành không được để trống";
    }

    if (assignmentTopic.value.isEmpty) {
      error.value = true;
      isAssignmentTopicError = true.obs;
      assignmentTopicError.value = "Chủ đề không được để trống";
    }

    if (assignmentDescription.text.trim().isEmpty) {
      error.value = true;
      isAssignmentDescriptionError = true.obs;
      assignmentDescriptionError.value = "Mô tả không được để trống";
    }

    if (error.value) return false;

    if (questions.isEmpty) {
      Get.dialog(
        const NotificationDialogWithoutButton(
          title: "Lỗi",
          message: "Bài tập phải có ít nhất 1 câu hỏi",
        ),
      );
      return false;
    }

    for (int i = 0; i < questions.length; i++) {
      if (questions[i]['question'].text.trim().isEmpty) {
        Get.dialog(
          NotificationDialogWithoutButton(
            title: "Lỗi",
            message: "Câu hỏi ${i + 1} không được để trống",
          ),
        );
        return false;
      }

      // Replace duration validation with score validation
      String scoreText = questions[i]['score'].text.trim();
      if (scoreText.isEmpty || !RegExp(r'^\d*\.?\d+$').hasMatch(scoreText)) {
        Get.dialog(
          NotificationDialogWithoutButton(
            title: "Lỗi",
            message: "Điểm số của câu hỏi ${i + 1} phải là số dương",
          ),
        );
        return false;
      }

      var answers = questions[i]['answers'] as RxList<Map<String, dynamic>>;

      if (answers.length < 2) {
        Get.dialog(
          NotificationDialogWithoutButton(
            title: "Lỗi",
            message: "Câu hỏi ${i + 1} phải có ít nhất 2 câu trả lời",
          ),
        );
        return false;
      }

      bool hasCorrectAnswer = false;
      for (int j = 0; j < answers.length; j++) {
        if (answers[j]['controller'].text.trim().isEmpty) {
          Get.dialog(
            NotificationDialogWithoutButton(
              title: "Lỗi",
              message: "Câu trả lời ${j + 1} của câu hỏi ${i + 1} không được để trống",
            ),
          );
          return false;
        }

        if ((answers[j]['isCorrect'] as RxBool).value) {
          hasCorrectAnswer = true;
        }
      }

      if (!hasCorrectAnswer) {
        Get.dialog(
          NotificationDialogWithoutButton(
            title: "Lỗi",
            message: "Câu hỏi ${i + 1} phải có ít nhất 1 câu trả lời đúng",
          ),
        );
        return false;
      }
    }

    return true;
  }

  void createQuiz() {
    // if (!validateQuiz()) return;
    var uri = Uri.parse("${Api.server}assignment/create");
    var response = MultipartRequest('POST', uri);
    if (assignmentType.value == 'quiz') {
      //assignment data
      response.fields['name'] = assignmentName.text.trim();
      response.headers['Authorization'] = 'Bearer $token';
      response.fields['description'] = assignmentDescription.text.trim();
      response.fields['status'] = assignmentStatus.value;
      response.fields['level'] = assignmentLevel.value;
      response.fields['totalScore'] = assignmentTotalScore.value;
      response.fields['specialized'] = assignmentSpecialized.value;
      response.fields['subject'] = assignmentSubject.text.trim();
      response.fields['topic'] = assignmentTopic.value;
      //question data
      List<Map<String, dynamic>> formattedQuestions = questions.map((question) {
        var answers = question['answers'] as RxList<Map<String, dynamic>>;
        return {
          'question': question['question'].text.trim(),
          'score': double.parse(question['score'].text.trim()),
          'choices': answers.map((answer) {
            return {
              'choice': answer['controller'].text.trim(),
              'is_correct': (answer['isCorrect'] as RxBool).value,
            };
          }).toList(),
        };
      }).toList();
      response.fields['questions'] = jsonEncode(formattedQuestions);
    }

    //homework data
    response.fields['class_id'] = classId.value.toString();
    response.fields['type'] = assignmentType.value;
    response.fields['name'] = assignmentName.text.trim();
    response.fields['link'] = '';
    response.fields['start_datetime'] = assignmentStartDate.text.trim();
    response.fields['due_datetime'] = assignmentDueDate.text.trim();
    response.fields['duration'] = assignmentDuration.value;
    response.fields['auto_grade'] = 'true';
    response.fields['status'] = 1.toString();

    // response.send().then((result) {
    //   if (result.statusCode == 200) {
    //   Get.snackbar("Success", "Quiz created successfully");
    //   clear();
    //   } else {
    //   Get.snackbar("Error", "Failed to create quiz");
    //   }
    // }).catchError((error) {
    //   Get.snackbar("Error", "Failed to create quiz");
    // });
  }

  void clear() {
    assignmentName.clear();
    isAssignmentNameError.value = false;
    assignmentSubject.clear();
    isAssignmentSubjectError.value = false;
    assignmentStatus.value = '';
    isAssignmentStatusError.value = false;
    assignmentLevel.value = '';
    isAssignmentLevelError.value = false;
    assignmentStartDate.clear();
    isAssignmentStartDateError.value = false;
    assignmentDueDate.clear();
    isAssignmentDueDateError.value = false;
    assignmentSpecialized.value = '';
    isAssignmentSpecializedError.value = false;
    assignmentTopic.value = '';
    isAssignmentTopicError.value = false;
    assignmentDescription.clear();
    isAssignmentDescriptionError.value = false;
    questions.clear();
    addNewQuestion();
  }
}
