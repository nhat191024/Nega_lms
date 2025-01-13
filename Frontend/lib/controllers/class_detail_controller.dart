import 'package:nega_lms/utils/imports.dart';

class ClassDetailController extends GetxController with GetSingleTickerProviderStateMixin {
  late TabController tabController;
  late PageController pageController;

  RxBool isLoading = true.obs;
  RxString role = ''.obs;
  RxString avatar = ''.obs;
  RxString username = ''.obs;
  RxBool isSubmitButtonLoading = false.obs;
  RxBool isUpdateQuizLoading = false.obs;
  RxBool createAssignmentThenPushToClass = false.obs;
  RxInt classId = 0.obs;
  RxString token = "".obs;
  RxString classCode = ''.obs;
  RxString className = ''.obs;
  RxString classDescription = ''.obs;
  RxString teacherName = ''.obs;
  RxString assignmentTitle = 'test'.obs;
  RxInt currentQuestion = 0.obs;

  RxString step = '1'.obs;

  RxList<HomeworkModel> assignmentList = <HomeworkModel>[].obs;
  RxList<HomeworkModel> assignmentsList = <HomeworkModel>[].obs;
  RxList<AssignmentModel> assignmentListForTeacher = <AssignmentModel>[].obs;
  RxList classPointList = [].obs;

  RxList<QuestionModel> questionList = <QuestionModel>[].obs;
  RxList<AnswerModel> answerList = <AnswerModel>[].obs;
  RxString timeLeft = '00:00:00'.obs;

  RxString assignmentType = ''.obs;
  RxString selectedPackage = ''.obs;

  RxString selectedAssignment = ''.obs;
  TextEditingController assignmentName = TextEditingController();
  TextEditingController assignmentStartDate = TextEditingController();
  TextEditingController assignmentDueDate = TextEditingController();
  TextEditingController assignmentDuration = TextEditingController();
  RxString assignmentStatus = ''.obs;
  TextEditingController assignmentDescription = TextEditingController();

  TextEditingController numberOfQuiz = TextEditingController();

  RxList<Map<String, dynamic>> questions = <Map<String, dynamic>>[].obs;

  TextEditingController linkSubmit = TextEditingController();

  List quizzes = [];
  List quizPackage = [];

  RxBool isAssignmentNameError = false.obs;
  RxBool isAssignmentStartDateError = false.obs;
  RxBool isAssignmentDueDateError = false.obs;
  RxBool isAssignmentDurationError = false.obs;
  RxBool isAssignmentStatusError = false.obs;
  RxBool isAssignmentDescriptionError = false.obs;

  RxBool isNumberOfQuizError = false.obs;

  RxBool isHomeworkScoreError = false.obs;
  RxBool isLinkSubmitError = false.obs;

  RxString assignmentNameError = ''.obs;
  RxString assignmentStartDateError = ''.obs;
  RxString assignmentDueDateError = ''.obs;
  RxString assignmentDurationError = ''.obs;
  RxString assignmentStatusError = ''.obs;
  RxString assignmentDescriptionError = ''.obs;

  RxString numberOfQuizError = ''.obs;

  RxString linkSubmitError = ''.obs;

  @override
  void onInit() {
    super.onInit();
    if (StorageService.checkData(key: "role")) {
      role.value = StorageService.readData(key: "role");
    }

    if (StorageService.checkData(key: "avatar")) {
      avatar.value = StorageService.readData(key: "avatar");
    }

    if (StorageService.checkData(key: "username")) {
      username.value = StorageService.readData(key: "username");
    }
    tabController = TabController(length: 3, vsync: this);
    pageController = PageController();
    WidgetsBinding.instance.addPostFrameCallback((_) async {
      if (!await Token.checkToken()) return;
      token.value = await Token.getToken();
      classId.value = Get.find<LayoutController>().selectedClassId.value;
      await fetchClassInfo(classId.value);
      await fetchClassAssignment(classId.value);
      if (role.value == "student") await fetchStudentAssignment();
    });
    // addNewQuestion();
  }

  fetchClassInfo(id) async {
    isLoading(true);
    try {
      String url = "${Api.server}classes/info/$id";
      var response = await get(Uri.parse(url), headers: {
        'Authorization': 'Bearer $token',
      }).timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        var data = jsonDecode(response.body);
        classCode.value = data['code'];
        className.value = data['name'];
        classDescription.value = data['description'];
        teacherName.value = data['teacherName'];
        isLoading(false);
      }
    } catch (e) {
      Get.snackbar("Error", "Failed to fetch class info");
    }
  }

  fetchClassAssignment(id) async {
    assignmentList.clear();
    try {
      isLoading(true);
      var roleN = role.value == 'student' ? 3 : 2;
      String url = "${Api.server}assignment/getByClass/$id/$roleN";
      var response = await get(Uri.parse(url), headers: {
        'Authorization': 'Bearer $token',
      }).timeout(const Duration(seconds: 10));
      if (response.statusCode == 200) {
        var data = jsonDecode(response.body);
        var assignmentData = data['assignments'];
        assignmentList.value = (assignmentData as List).map((e) => HomeworkModel.fromMap(e)).toList();
      }
    } finally {
      isLoading(false);
    }
  }

  fetchClassPoint(id) async {
    try {
      String url = "${Api.server}classes/point/$id";
      var response = await get(Uri.parse(url), headers: {
        'Authorization': 'Bearer $token',
      }).timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        var data = jsonDecode(response.body);
        classPointList.value = data['submissions'];
      }
    } catch (e) {
      Get.snackbar("Error", "Failed to fetch class info");
    }
  }

  fetchStudentAssignment() async {
    try {
      String url = "${Api.server}classes/getStudentAssignmentPoint/${classId.value}";
      var response = await get(Uri.parse(url), headers: {
        'Authorization': 'Bearer $token',
      }).timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        var data = jsonDecode(response.body);
        classPointList.value = data;
      }
    } catch (e) {
      Get.snackbar("Error", "Failed to fetch class info");
    }
  }

  step2() async {
    try {
      quizPackage.clear();
      String url = "${Api.server}quizPackage/teacher-quiz-package";
      var response = await get(Uri.parse(url), headers: {
        'Authorization': 'Bearer $token',
      }).timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        var data = jsonDecode(response.body);
        for (var item in data) {
          quizPackage.add(item);
        }

        step.value = '2';
      }
    } catch (e) {
      Get.snackbar("Error", "Failed to fetch class info");
    }
  }

  void submitAssignment(String type, String id) async {
    isSubmitButtonLoading.value = true;
    var uri = Uri.parse("${Api.server}assignment/submit");
    var request = MultipartRequest('POST', uri);
    request.headers['Authorization'] = 'Bearer $token';
    request.headers['Content-Type'] = 'application/json';
    request.headers['Accept'] = 'application/json';

    request.fields['type'] = type;
    request.fields['assignment_id'] = id;
    request.fields['link'] = linkSubmit.text.trim();
    request.fields['class_id'] = classId.value.toString();

    var streamedResponse = await request.send();
    if (streamedResponse.statusCode == 200) {
      isSubmitButtonLoading.value = false;
      Get.back();
      fetchClassAssignment(classId.value);
      Get.snackbar("Thành công", "Nộp bài thành công", maxWidth: Get.width * 0.2);
    } else {
      isSubmitButtonLoading.value = false;
      Get.snackbar("Lỗi", "Đã có lỗi xảy ra", maxWidth: Get.width * 0.2);
    }
  }

  void addNewQuestion() {
    double scorePerQuestion = 10.0 / (questions.length + 1);
    for (var question in questions) {
      question['score'].text = scorePerQuestion.toStringAsFixed(2);
    }

    questions.add({
      'question': TextEditingController(),
      'score': TextEditingController(text: scorePerQuestion.toStringAsFixed(2)),
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

  void removeQuestion(int index) {
    if (questions.length > 1) {
      questions.removeAt(index);
      double scorePerQuestion = 10.0 / questions.length;
      for (var question in questions) {
        question['score'].text = scorePerQuestion.toStringAsFixed(2);
      }
    } else {
      Get.dialog(
        const NotificationDialogWithoutButton(
          title: "Lỗi",
          message: "Bài tập phải có ít nhất 1 câu hỏi",
        ),
      );
    }
  }

  void removeAnswer(int questionIndex, int answerIndex) {
    var answers = questions[questionIndex]['answers'] as RxList<Map<String, dynamic>>;
    if (answers.length > 2) {
      answers.removeAt(answerIndex);
      questions.refresh();
    } else {
      Get.dialog(
        const NotificationDialogWithoutButton(
          title: "Lỗi",
          message: "Câu hỏi phải có ít nhất 2 câu trả lời",
        ),
      );
    }
  }

  bool validateQuiz() {
    RxBool error = false.obs;
    var vietnameseRegex = RegExp(
        r'^[a-zA-ZÃ€ÃÃ‚ÃƒÃˆÃ‰ÃŠÃŒÃÃ’Ã“Ã”Ã•Ã™ÃšÄ‚ÄÄ¨Å¨Æ Ã Ã¡Ã¢Ã£Ã¨Ã©ÃªÃ¬Ã­Ã²Ã³Ã´ÃµÃ¹ÃºÄƒÄ‘Ä©Å©Æ¡Æ¯Ä‚áº áº¢áº¤áº¦áº¨áºªáº¬áº®áº°áº²áº´áº¶áº¸áººáº¼á»€á»€á»‚áº¾Æ°Äƒáº¡áº£áº¥áº§áº©áº«áº­áº¯áº±áº³áºµáº·áº¹áº»áº½á»á»á»ƒáº¿á»„á»†á»ˆá»Šá»Œá»Žá»á»’á»”á»–á»˜á»šá»œá»žá» á»¢á»¤á»¦á»¨á»ªá»…á»‡á»‰á»‹á»á»á»‘á»“á»•á»—á»™á»›á»á»Ÿá»¡á»£á»¥á»§á»©á»«á»¬á»®á»°á»²á»´Ãá»¶á»¸á»­á»¯á»±á»³á»µá»·á»¹\s\W|_\d]+$');

    void setError(RxBool errorField, RxString errorMessage, String message) {
      error.value = true;
      errorField.value = true;
      errorMessage.value = message;
    }

    void isOverTimeToday(String date, RxBool errorField, RxString errorMessage, String message) {
      DateTime now = DateTime.now();
      DateTime selectedDate = DateTime.parse(date);
      if (selectedDate.isBefore(now)) {
        setError(errorField, errorMessage, message);
      }
    }

    //title validation
    if (assignmentName.text.trim().isEmpty) {
      setError(isAssignmentNameError, assignmentNameError, "Tên bài tập không được để trống");
    } else if (assignmentName.text.trim().length > 255) {
      setError(isAssignmentNameError, assignmentNameError, "Tên bài tập không được quá 255 ký tự");
    } else if (!vietnameseRegex.hasMatch(assignmentName.text.trim())) {
      setError(isAssignmentNameError, assignmentNameError, "Tên bài tập chỉ chứa ký tự chữ và số");
    }

    //start date validation
    if (assignmentStartDate.text.trim().isEmpty) {
      setError(isAssignmentStartDateError, assignmentStartDateError, "Ngày bắt đầu không được để trống");
    } else {
      isOverTimeToday(
        assignmentStartDate.text.trim(),
        isAssignmentStartDateError,
        assignmentStartDateError,
        "Ngày bắt đầu không được lớn hơn ngày hiện tại",
      );
    }

    //due date validation
    if (assignmentDueDate.text.trim().isEmpty) {
      setError(isAssignmentDueDateError, assignmentDueDateError, "Ngày kết thúc không được để trống");
    } else {
      isOverTimeToday(
        assignmentDueDate.text.trim(),
        isAssignmentDueDateError,
        assignmentDueDateError,
        "Ngày kết thúc không được lớn hơn ngày hiện tại",
      );
    }

    //duration validation
    if (assignmentType.value == "quiz") {
      if (assignmentDuration.text.trim().isEmpty) {
        setError(isAssignmentDurationError, assignmentDurationError, "Thời lượng không được để trống");
      } else if (!RegExp(r'^\d+$').hasMatch(assignmentDuration.text.trim())) {
        setError(isAssignmentDurationError, assignmentDurationError, "Thời lượng phải là số dương");
      } else if (assignmentStartDate.text.isNotEmpty && assignmentDueDate.text.isNotEmpty) {
        DateTime startDate = DateTime.parse(assignmentStartDate.text.trim());
        DateTime dueDate = DateTime.parse(assignmentDueDate.text.trim());
        int durationMinutes = int.parse(assignmentDuration.text.trim());
        int diffMinutes = dueDate.difference(startDate).inMinutes;

        if (diffMinutes < durationMinutes) {
          setError(
            isAssignmentDurationError,
            assignmentDurationError,
            "Thời lượng không được lớn hơn khoảng thời gian giữa ngày bắt đầu và kết thúc",
          );
          setError(
            isAssignmentStartDateError,
            assignmentStartDateError,
            "Thời lượng không được lớn hơn khoảng thời gian giữa ngày bắt đầu và kết thúc",
          );
          setError(
            isAssignmentDueDateError,
            assignmentDueDateError,
            "Thời lượng không được lớn hơn khoảng thời gian giữa ngày bắt đầu và kết thúc",
          );
        }
      }
    }

    //description validation
    if (assignmentDescription.text.trim().isEmpty) {
      setError(isAssignmentDescriptionError, assignmentDescriptionError, "Mô tả không được để trống");
    } else if (assignmentDescription.text.trim().length > 255) {
      setError(isAssignmentDescriptionError, assignmentDescriptionError, "Mô tả không được quá 255 ký tự");
    } else if (!vietnameseRegex.hasMatch(assignmentDescription.text.trim())) {
      setError(isAssignmentDescriptionError, assignmentDescriptionError, "Mô tả chỉ chứa ký tự chữ và số");
    }

    if (error.value) return false;

    //   for (int i = 0; i < questions.length; i++) {
    //     if (questions[i]['question'].text.trim().isEmpty) {
    //       Get.dialog(
    //         NotificationDialogWithoutButton(
    //           title: "Lỗi",
    //           message: "Câu hỏi ${i + 1} không được để trống",
    //         ),
    //       );
    //       return false;
    //     }

    //     var answers = questions[i]['answers'] as RxList<Map<String, dynamic>>;

    //     if (answers.length < 2) {
    //       Get.dialog(
    //         NotificationDialogWithoutButton(
    //           title: "Lỗi",
    //           message: "Câu hỏi ${i + 1} phải có ít nhất 2 câu trả lời",
    //         ),
    //       );
    //       return false;
    //     }

    //     bool hasCorrectAnswer = false;
    //     for (int j = 0; j < answers.length; j++) {
    //       if (answers[j]['controller'].text.trim().isEmpty) {
    //         Get.dialog(
    //           NotificationDialogWithoutButton(
    //             title: "Lỗi",
    //             message: "Câu trả lời ${j + 1} của câu hỏi ${i + 1} không được để trống",
    //           ),
    //         );
    //         return false;
    //       }

    //       if ((answers[j]['isCorrect'] as RxBool).value) {
    //         hasCorrectAnswer = true;
    //       }
    //     }

    //     if (!hasCorrectAnswer) {
    //       Get.dialog(
    //         NotificationDialogWithoutButton(
    //           title: "Lỗi",
    //           message: "Câu hỏi ${i + 1} phải có ít nhất 1 câu trả lời đúng",
    //         ),
    //       );
    //       return false;
    //     }
    //   }
    // }

    return true;
  }

  bool validateQuizNumber(String value) {
    if (value.isEmpty) {
      isNumberOfQuizError.value = true;
      numberOfQuizError.value = "Số lượng câu hỏi không được để trống";
      return false;
    }

    if (!value.isNumericOnly) {
      isNumberOfQuizError.value = true;
      numberOfQuizError.value = "Số lượng câu hỏi phải là số"; 
      return false;
    }

    int? parsedValue = int.tryParse(value);
    if (parsedValue == null) {
      isNumberOfQuizError.value = true;
      numberOfQuizError.value = "Số lượng câu hỏi phải là số";
      return false;
    }

    if (parsedValue < 5) {
      isNumberOfQuizError.value = true;
      numberOfQuizError.value = "Số lượng câu hỏi phải lớn hơn 5";
      return false;
    } else if (parsedValue > quizPackage[int.tryParse(selectedPackage.value) ?? 0]["totalQuizzes"]) {
      isNumberOfQuizError.value = true;
      numberOfQuizError.value =
          "Số lượng câu hỏi phải nhỏ hơn tổng số câu hỏi trong bộ (${quizPackage[int.tryParse(selectedPackage.value) ?? 0]["totalQuizzes"].toString()})";
      return false;
    } else {
      isNumberOfQuizError.value = false;
      return true;
    }
  }

  createQuiz() async {
    if (!validateQuiz()) return;
    if (assignmentType.value == "quiz" && !validateQuizNumber(numberOfQuiz.text.trim())) return;
    var uri = Uri.parse("${Api.server}assignment/create");
    var request = MultipartRequest('POST', uri);
    request.headers['Authorization'] = 'Bearer $token';
    request.headers['Content-Type'] = 'application/json';
    request.headers['Accept'] = 'application/json';

    request.fields['class_id'] = classId.value.toString();
    request.fields['title'] = assignmentName.text.trim();
    request.fields['start_datetime'] = assignmentStartDate.text.trim();
    request.fields['due_datetime'] = assignmentDueDate.text.trim();
    request.fields['duration'] = assignmentDuration.text.trim();
    request.fields['status'] = assignmentStatus.value == 'true' ? "published" : "closed";
    request.fields['description'] = assignmentDescription.text.trim();
    request.fields['type'] = assignmentType.value;
    if (assignmentType.value == "quiz") request.fields['quiz_package_id'] = quizPackage[int.tryParse(selectedPackage.value) ?? 0]["id"].toString();
    if (assignmentType.value == "quiz") request.fields['number_of_questions'] = numberOfQuiz.text.trim();

    var streamedResponse = await request.send();
    if (streamedResponse.statusCode == 201) {
      clear();
      Get.back();
      fetchClassAssignment(classId.value);
      Get.snackbar("Thành công", "Tạo bài tập thành công", maxWidth: Get.width * 0.2);
    } else {
      RxString errors = ''.obs;
      Get.snackbar("Lỗi", errors.value, maxWidth: Get.width * 0.2);
    }
  }

  Future loadDataToEdit(String id, String type) async {
    clear();
    try {
      String url = "${Api.server}assignment/getById/$id/1";
      var response = await get(Uri.parse(url), headers: {
        'Authorization': 'Bearer $token',
      }).timeout(const Duration(seconds: 10));
      if (response.statusCode == 200) {
        var data = jsonDecode(response.body);
        var assignmentData = data['assignment'];

        selectedAssignment = id.obs;
        assignmentName.text = assignmentData['title'];
        assignmentStartDate.text = assignmentData['startDate'];
        assignmentDueDate.text = assignmentData['dueDate'];
        if (type == 'quiz') assignmentDuration.text = assignmentData['duration'].toString();
        assignmentStatus.value = assignmentData['status'] == "published" ? 'true' : 'false';
        assignmentDescription.text = assignmentData['description'];

        for (var question in assignmentData['questions']) {
          quizzes.add(question);
        }
      }
    } catch (e) {
      Get.snackbar("Error", "Failed to fetch homework info");
    }
  }

  void updateQuiz(String id, String type) async {
    assignmentType.value = type;
    if (!validateQuiz()) return;
    var uri = Uri.parse("${Api.server}assignment/update");
    var response = MultipartRequest('POST', uri);
    response.headers['Authorization'] = 'Bearer $token';
    response.headers['Content-Type'] = 'application/json';
    response.headers['Accept'] = 'application/json';

    response.fields['id'] = id;
    response.fields['title'] = assignmentName.text.trim();
    response.fields['start_datetime'] = assignmentStartDate.text.trim();
    response.fields['due_datetime'] = assignmentDueDate.text.trim();
    if (type == 'quiz') response.fields['duration'] = assignmentDuration.text.trim();
    response.fields['status'] = assignmentStatus.value == 'true' ? "published" : "closed";
    response.fields['description'] = assignmentDescription.text.trim();

    var streamedResponse = await response.send();

    if (streamedResponse.statusCode == 200) {
      clear();
      Get.back();
      fetchClassAssignment(classId.value);
      Get.snackbar("Thành công", "Cập nhật bài tập thành công", maxWidth: Get.width * 0.2);
    } else {
      Get.snackbar("Lỗi", "Cập nhật bài tập thất bại", maxWidth: Get.width * 0.2);
    }
  }

  updateQuizzes() async {
    isUpdateQuizLoading.value = true;
    try {
      var uri = Uri.parse("${Api.server}assignment/update-assignment-quiz");
      var request = MultipartRequest('POST', uri);
      request.headers['Authorization'] = 'Bearer $token';
      request.headers['Content-Type'] = 'application/json';
      request.headers['Accept'] = 'application/json';

      request.fields['class_assignment_id'] = selectedAssignment.value;
      request.fields['quiz_package_id'] = quizPackage[int.tryParse(selectedPackage.value) ?? 0]["id"].toString();
      request.fields['number_of_questions'] = numberOfQuiz.text.trim();

      var streamedResponse = await request.send();

      if (streamedResponse.statusCode == 200) {
        isSubmitButtonLoading.value = false;
        Get.back();
        Get.snackbar("Thành công", "Cập nhật bài tập thành công", maxWidth: Get.width * 0.2);
      } else {
        Get.snackbar("Lỗi", "Đã có lỗi xảy ra", maxWidth: Get.width * 0.2);
      }
    } finally {
      isUpdateQuizLoading.value = false;
    }
  }

  void clear() {
    assignmentName.clear();
    isAssignmentNameError.value = false;
    assignmentStatus.value = 'true';
    isAssignmentStatusError.value = false;
    assignmentStartDate.clear();
    isAssignmentStartDateError.value = false;
    assignmentDueDate.clear();
    isAssignmentDueDateError.value = false;
    assignmentDescription.clear();
    isAssignmentDescriptionError.value = false;
    assignmentDuration.clear();
    isAssignmentDurationError.value = false;

    numberOfQuiz.clear();
    isNumberOfQuizError.value = false;

    quizzes.clear();
    quizPackage.clear();

    step.value = '1';
    selectedPackage.value = '';
    questions.clear();
    addNewQuestion();
  }
}
