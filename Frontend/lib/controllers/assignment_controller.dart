import 'package:nega_lms/utils/imports.dart';

class AssignmentController extends GetxController {
  RxBool isLoading = true.obs;
  RxString assignmentId = ''.obs;
  RxString classId = ''.obs;
  RxString token = "".obs;
  RxString assignmentTitle = 'test'.obs;
  RxInt assignmentDuration = 0.obs;
  RxInt currentQuestion = 0.obs;
  Rx<HomeworkModel> assignment = HomeworkModel(
    id: 0,
    homeworkId: 0,
    creatorName: '',
    name: '',
    description: '',
    duration: 0,
    startDate: '',
    dueDate: '',
    type: '',
    isSubmitted: false,
  ).obs;
  RxList<QuestionModel> questionList = <QuestionModel>[].obs;
  RxList<AnswerModel> answerList = <AnswerModel>[].obs;
  RxString timeLeft = '00:00:00'.obs;
  Timer? _timer;

  ClassDetailController classDetailController = Get.find<ClassDetailController>();

  @override
  void onInit() async {
    super.onInit();
    WidgetsBinding.instance.addPostFrameCallback((_) async {
      if (!await Token.checkToken()) return;
      token.value = await Token.getToken();
      final arguments = Get.arguments as Map<String, dynamic>;
      assignmentId.value = arguments['assignment_id'].toString();
      classId.value = arguments['class_id'].toString();
      await fetchAssignment(assignmentId.value, classId.value);
    });
  }

  fetchAssignment(assignmentId, classId) async {
    try {
      isLoading(true);
      String url = "${Api.server}assignment/detail/$assignmentId/$classId";
      var response = await get(Uri.parse(url), headers: {
        'Authorization': 'Bearer $token',
      }).timeout(const Duration(seconds: 10));
      if (response.statusCode == 200) {
        var data = jsonDecode(response.body)['assignment'];
        var question = data['questions'];
        assignment.value = HomeworkModel.fromMap(data);
        questionList.value = (question as List).map((e) => QuestionModel.fromMap(e)).toList();
        assignmentDuration.value = assignment.value.duration!;
        loadChoiceToAnswerList();
        int duration = (assignmentDuration * 60).toInt();
        startTimer(duration);
      }
    } catch (e) {
      questionList.clear();
    } finally {
      isLoading(false);
    }
  }

  submitAssignment() async {
    try {
      isLoading(true);
      String url = "${Api.server}assignment/submit";
      var request = MultipartRequest('POST', Uri.parse(url));
      request.headers['Authorization'] = 'Bearer $token';
      request.headers['Content-Type'] = 'application/json';
      request.headers['Accept'] = 'application/json';
      request.fields['assignment_id'] = assignmentId.value;
      request.fields['answers'] = jsonEncode(answerList.map((e) => e.toMap()).toList());
      var response = await request.send().timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        classDetailController.fetchClassAssignment(classId.value);
        Get.back();
        clear();
        Get.snackbar(
          'Success',
          'Assignment submitted successfully',
        );
      } else {
        Get.back();
        clear();
        Get.snackbar(
          'Error',
          'Failed to submit assignment',
        );
      }
    } finally {
      isLoading(false);
    }
  }

  void loadChoiceToAnswerList() {
    for (var question in questionList) {
      saveSelection(question.id!, 0);
    }
  }

  void saveSelection(int questionId, int choiceId) {
    var answer = answerList.firstWhere(
      (element) => element.questionId == questionId,
      orElse: () => AnswerModel(questionId: questionId),
    );
    answer.choiceId = choiceId;
    if (!answerList.contains(answer)) {
      answerList.add(answer);
    } else {
      int index = answerList.indexOf(answer);
      answerList[index] = answer;
    }
    answerList.refresh();
  }

  void startTimer(int seconds) {
    const duration = Duration(seconds: 1);
    var remainingSeconds = seconds;

    _timer?.cancel();
    _timer = Timer.periodic(duration, (Timer timer) {
      if (remainingSeconds == 0) {
        timer.cancel();
      } else {
        remainingSeconds--;
        int hours = remainingSeconds ~/ 3600;
        int minutes = (remainingSeconds % 3600) ~/ 60;
        int secs = remainingSeconds % 60;

        timeLeft.value = '${hours.toString().padLeft(2, '0')}:'
            '${minutes.toString().padLeft(2, '0')}:'
            '${secs.toString().padLeft(2, '0')}';
      }
    });
  }

  void clear() {
    _timer?.cancel();
    assignmentId.value = '';
    classId.value = '';
    token.value = '';
    assignmentTitle.value = 'test';
    assignmentDuration.value = 0;
    currentQuestion.value = 0;
    assignment.value = HomeworkModel(
      id: 0,
      homeworkId: 0,
      creatorName: '',
      name: '',
      description: '',
      duration: 0,
      startDate: '',
      dueDate: '',
      type: '',
      isSubmitted: false,
    );
    questionList.clear();
    answerList.clear();
    timeLeft.value = '00:00:00';
  }

  @override
  void onClose() {
    _timer?.cancel();
    super.onClose();
  }
}
