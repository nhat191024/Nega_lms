import 'package:nega_lms/utils/imports.dart';

class ClassDetailController extends GetxController with GetSingleTickerProviderStateMixin {
  late TabController tabController;
  RxBool isLoading = true.obs;
  RxBool isSubmitButtonLoading = false.obs;
  RxBool createAssignmentThenPushToClass = false.obs;
  RxInt classId = 0.obs;
  RxString token = "".obs;
  RxString classCode = ''.obs;
  RxString className = ''.obs;
  RxString classDescription = ''.obs;
  RxString teacherName = ''.obs;
  RxString assignmentTitle = 'test'.obs;
  RxInt currentQuestion = 0.obs;

  RxList<HomeworkModel> assignmentList = <HomeworkModel>[].obs;
  RxList<HomeworkModel> assignmentsList = <HomeworkModel>[].obs;
  RxList<AssignmentModel> assignmentListForTeacher = <AssignmentModel>[].obs;

  RxList<QuestionModel> questionList = <QuestionModel>[].obs;
  RxList<AnswerModel> answerList = <AnswerModel>[].obs;
  RxString timeLeft = '00:00:00'.obs;

  RxString assignmentType = ''.obs;
  RxString selectedAssignment = ''.obs;

  TextEditingController assignmentName = TextEditingController();
  TextEditingController assignmentSubject = TextEditingController();
  RxString assignmentStatus = ''.obs;
  RxString assignmentLevel = ''.obs;
  TextEditingController assignmentDuration = TextEditingController();
  RxString assignmentAutoGrade = ''.obs;
  TextEditingController assignmentStartDate = TextEditingController();
  TextEditingController assignmentDueDate = TextEditingController();
  RxString assignmentSpecialized = ''.obs;
  RxString assignmentTopic = ''.obs;
  TextEditingController assignmentDescription = TextEditingController();
  RxList<Map<String, dynamic>> questions = <Map<String, dynamic>>[].obs;

  TextEditingController homeworkScore = TextEditingController();
  TextEditingController linkSubmit = TextEditingController();

  RxBool isAssignmentNameError = false.obs;
  RxBool isAssignmentSubjectError = false.obs;
  RxBool isAssignmentStatusError = false.obs;
  RxBool isAssignmentLevelError = false.obs;
  RxBool isAssignmentDurationError = false.obs;
  RxBool isAssignmentAutoGrade = false.obs;
  RxBool isAssignmentStartDateError = false.obs;
  RxBool isAssignmentDueDateError = false.obs;
  RxBool isAssignmentSpecializedError = false.obs;
  RxBool isAssignmentTopicError = false.obs;
  RxBool isAssignmentDescriptionError = false.obs;

  RxBool isHomeworkScoreError = false.obs;
  RxBool isLinkSubmitError = false.obs;

  RxString assignmentNameError = ''.obs;
  RxString assignmentSubjectError = ''.obs;
  RxString assignmentStatusError = ''.obs;
  RxString assignmentLevelError = ''.obs;
  RxString assignmentDurationError = ''.obs;
  RxString assignmentAutoGradeError = ''.obs;
  RxString assignmentStartDateError = ''.obs;
  RxString assignmentDueDateError = ''.obs;
  RxString assignmentSpecializedError = ''.obs;
  RxString assignmentTopicError = ''.obs;
  RxString assignmentDescriptionError = ''.obs;

  RxString homeworkScoreError = ''.obs;
  RxString linkSubmitError = ''.obs;

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
      await fetchAllClassAssignment(classId.value);
      await fetchAssignmentForTeacher();
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
    assignmentList.clear();
    try {
      isLoading(true);
      String url = "${Api.server}assignment/$id/2";
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

  fetchAllClassAssignment(id) async {
    assignmentsList.clear();
    try {
      isLoading(true);
      String url = "${Api.server}assignment/$id/1";
      var response = await get(Uri.parse(url), headers: {
        'Authorization': 'Bearer $token',
      }).timeout(const Duration(seconds: 10));
      if (response.statusCode == 200) {
        var data = jsonDecode(response.body);
        var assignmentData = data['assignments'];
        assignmentsList.value = (assignmentData as List).map((e) => HomeworkModel.fromMap(e)).toList();
      }
    } finally {
      isLoading(false);
    }
  }

  fetchAssignmentForTeacher() async {
    assignmentListForTeacher.clear();
    try {
      String url = "${Api.server}assignment/getForTeacher";
      var response = await get(Uri.parse(url), headers: {
        'Authorization': 'Bearer $token',
      }).timeout(const Duration(seconds: 10));
      if (response.statusCode == 200) {
        var data = jsonDecode(response.body);
        var assignmentData = data['assignments'];
        assignmentListForTeacher.value = (assignmentData as List).map((e) => AssignmentModel.fromMap(e)).toList();
      }
    } catch (e) {
      Get.snackbar("Error", "Failed to fetch assignment for teacher");
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

    // Validate common fields
    if (createAssignmentThenPushToClass.value == true) {
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

      if (assignmentType.value == 'quiz' || assignmentType.value == 'quiz_bank') {
        if (assignmentAutoGrade.value.isEmpty) {
          setError(isAssignmentAutoGrade, assignmentAutoGradeError, "Tự động chấm điểm không được để trống");
        }
      }
    }

    // Validate quiz specific fields
    if (assignmentType.value == 'quiz') {
      if (assignmentSubject.text.trim().isEmpty) {
        setError(isAssignmentSubjectError, assignmentSubjectError, "Chủ đề không được để trống");
      } else if (assignmentSubject.text.trim().length > 255) {
        setError(isAssignmentSubjectError, assignmentSubjectError, "Chủ đề không được quá 255 ký tự");
      } else if (!vietnameseRegex.hasMatch(assignmentSubject.text.trim())) {
        setError(isAssignmentSubjectError, assignmentSubjectError, "Chủ đề chỉ chứa ký tự chữ và số");
      }

      if (assignmentLevel.value.isEmpty) {
        setError(isAssignmentLevelError, assignmentLevelError, "Cấp độ không được để trống");
      }

      if (assignmentSpecialized.value.isEmpty) {
        setError(isAssignmentSpecializedError, assignmentSpecializedError, "Chuyên ngành không được để trống");
      }

      if (assignmentTopic.value.isEmpty) {
        setError(isAssignmentTopicError, assignmentTopicError, "Chủ đề không được để trống");
      }
    }

    // Validate common fields for quiz and quiz_bank
    if (assignmentType.value == 'quiz' || assignmentType.value == 'quiz_bank') {
      if (assignmentStatus.value.isEmpty) {
        setError(isAssignmentStatusError, assignmentStatusError, "Trạng thái không được để trống");
      }
    }

    // Validate common fields for quiz and link
    if (assignmentType.value == 'quiz' || assignmentType.value == 'link') {
      if (assignmentName.text.trim().isEmpty) {
        setError(isAssignmentNameError, assignmentNameError, "Tên bài tập không được để trống");
      } else if (assignmentName.text.trim().length > 255) {
        setError(isAssignmentNameError, assignmentNameError, "Tên bài tập không được quá 255 ký tự");
      } else if (!vietnameseRegex.hasMatch(assignmentName.text.trim())) {
        setError(isAssignmentNameError, assignmentNameError, "Tên bài tập chỉ chứa ký tự chữ và số");
      }

      if (assignmentDescription.text.trim().isEmpty) {
        setError(isAssignmentDescriptionError, assignmentDescriptionError, "Mô tả không được để trống");
      } else if (assignmentDescription.text.trim().length > 255) {
        setError(isAssignmentDescriptionError, assignmentDescriptionError, "Mô tả không được quá 255 ký tự");
      } else if (!vietnameseRegex.hasMatch(assignmentDescription.text.trim())) {
        setError(isAssignmentDescriptionError, assignmentDescriptionError, "Mô tả chỉ chứa ký tự chữ và số");
      }
    }

    // Validate link specific fields
    if (assignmentType.value == 'link') {
      if (homeworkScore.text.trim().isEmpty) {
        setError(isHomeworkScoreError, homeworkScoreError, "Điểm số không được để trống");
      } else if (!RegExp(r'^\d*\.?\d+$').hasMatch(homeworkScore.text.trim())) {
        setError(isHomeworkScoreError, homeworkScoreError, "Điểm số phải là số dương");
      }
    }

    if (error.value) return false;

    // Validate quiz_bank specific fields
    if (assignmentType.value == 'quiz_bank') {
      if (selectedAssignment.value.isEmpty) {
        Get.dialog(
          const NotificationDialogWithoutButton(
            title: "Lỗi",
            message: "Bạn phải chọn 1 bài tập từ ngân hàng câu hỏi",
          ),
        );
        return false;
      }
    }

    // Validate quiz questions and answers
    if (assignmentType.value == 'quiz') {
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
    }

    return true;
  }

  void createQuiz() async {
    if (!validateQuiz()) return;
    var uri = Uri.parse("${Api.server}assignment/create");
    var response = MultipartRequest('POST', uri);
    response.headers['Authorization'] = 'Bearer $token';
    response.headers['Content-Type'] = 'application/json';
    response.headers['Accept'] = 'application/json';
    response.fields['create_homework'] = createAssignmentThenPushToClass.value.toString();

    if (assignmentType.value == 'quiz') {
      double totalScore = 0.0;
      List<Map<String, dynamic>> formattedQuestions = questions.map((question) {
        var answers = question['answers'] as RxList<Map<String, dynamic>>;
        totalScore += double.parse(question['score'].text.trim());
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

      //assignment data
      response.fields['title'] = assignmentName.text.trim();
      response.fields['description'] = assignmentDescription.text.trim();
      response.fields['status'] = assignmentStatus.value.toString();
      response.fields['level'] = assignmentLevel.value;
      response.fields['totalScore'] = totalScore.toString();
      response.fields['specialized'] = assignmentSpecialized.value;
      response.fields['subject'] = assignmentSubject.text.trim();
      response.fields['topic'] = assignmentTopic.value;
      String questionsJson = jsonEncode(formattedQuestions);
      response.fields['questions'] = questionsJson;
    }

    //homework data
    response.fields['type'] = assignmentType.value == 'link' ? 'link' : 'quiz';
    if (createAssignmentThenPushToClass.value || assignmentType.value == 'link') {
      if (selectedAssignment.value.isNotEmpty) response.fields['assignment_id'] = selectedAssignment.value;
      response.fields['class_id'] = classId.value.toString();
      if (assignmentType.value == 'link') response.fields['title'] = assignmentName.text.trim();
      if (assignmentType.value == 'link') response.fields['score'] = homeworkScore.text.trim();
      if (assignmentType.value == 'link') response.fields['description'] = assignmentDescription.text.trim();
      response.fields['start_datetime'] = assignmentStartDate.text.trim();
      response.fields['due_datetime'] = assignmentDueDate.text.trim();
      response.fields['duration'] = assignmentDuration.text.trim();
      if (assignmentType.value == 'quiz' || assignmentType.value == 'quiz_bank') response.fields['auto_grade'] = assignmentAutoGrade.value;
      response.fields['homework_status'] = 1.toString();
    }

    var streamedResponse = await response.send();
    if (streamedResponse.statusCode == 201) {
      clear();
      Get.back();
      fetchClassAssignment(classId.value);
      fetchAllClassAssignment(classId.value);
      fetchAssignmentForTeacher();
      Get.snackbar("Thành công", "Tạo bài tập thành công", maxWidth: Get.width * 0.2);
    } else {
      RxString errors = ''.obs;
      var responseString = await streamedResponse.stream.bytesToString();
      var data = jsonDecode(responseString);
      for (var error in data['error']) {
        errors.value += '${error.value}\n';
      }

      Get.snackbar("Lỗi", errors.value, maxWidth: Get.width * 0.2);
    }
  }

  Future loadDataToEdit(String id, String type) async {
    clear();
    try {
      String url = "${Api.server}assignment/get/$id";
      var response = await get(Uri.parse(url), headers: {
        'Authorization': 'Bearer $token',
      }).timeout(const Duration(seconds: 10));
      if (response.statusCode == 200) {
        var data = jsonDecode(response.body);
        var assignmentData = data['homework'];
        if (type == 'quiz') {
          assignmentDuration.text = assignmentData['duration'].toString();
          assignmentAutoGrade.value = assignmentData['autoGrade'] == 1 ? 'true' : 'false';
          assignmentStartDate.text = assignmentData['startDate'];
          assignmentDueDate.text = assignmentData['dueDate'];
          assignmentStatus.value = assignmentData['status'] == 1 ? 'true' : 'false';
          selectedAssignment.value = assignmentData['assignmentId'].toString();
        } else {
          assignmentName.text = assignmentData['title'];
          assignmentDuration.text = assignmentData['duration'].toString();
          homeworkScore.text = assignmentData['score'].toString();
          assignmentStartDate.text = assignmentData['startDate'];
          assignmentDueDate.text = assignmentData['dueDate'];
          assignmentDescription.text = assignmentData['description'];
        }
      }
    } catch (e) {
      Get.snackbar("Error", "Failed to fetch homework info");
    }
  }

  void updateQuiz() async {

  }

  void clear() {
    assignmentName.clear();
    isAssignmentNameError.value = false;
    assignmentSubject.clear();
    isAssignmentSubjectError.value = false;
    assignmentStatus.value = 'true';
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
    assignmentDuration.clear();
    isAssignmentDurationError.value = false;
    assignmentAutoGrade.value = 'true';
    isAssignmentAutoGrade.value = false;
    homeworkScore.clear();
    isHomeworkScoreError.value = false;
    linkSubmit.clear();
    isLinkSubmitError.value = false;
    assignmentType.value = '';
    createAssignmentThenPushToClass.value = false;
    selectedAssignment.value = '';
    questions.clear();
    addNewQuestion();
  }
}
