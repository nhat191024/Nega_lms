import 'package:nega_lms/utils/imports.dart';

class AssignmentController extends GetxController {
  final TextEditingController searchController = TextEditingController();
  RxBool isLoading = true.obs;
  late final String? assignmentId;
  RxString assignmentTitle = 'test'.obs;
  RxInt assignmentDuration = 0.obs;
  RxInt currentQuestion = 0.obs;
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
  Timer? _timer;

  @override
  void onInit() async {
    assignmentId = Get.parameters['assignment_id'];
    await fetchAssignment();
    int duration = (assignmentDuration * 60).toInt();
    startTimer(duration);
    loadChoiceToAnswerList();
    super.onInit();
  }

  fetchAssignment() async {
    try {
      isLoading(true);
      String url = "${Api.server}assignment/detail/$assignmentId";
      var response = await get(Uri.parse(url), headers: {
        'Authorization': 'Bearer ${Api.testToken}',
      }).timeout(const Duration(seconds: 10));
      if (response.statusCode == 200) {
        var data = jsonDecode(response.body)['assignment'];
        var question = data['questions'];
        assignment.value = AssignmentModel.fromMap(data);
        questionList.value = (question as List).map((e) => QuestionModel.fromMap(e)).toList();
        assignmentDuration.value = assignment.value.duration!;
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
      var response = await post(Uri.parse(url), headers: {
        'Authorization': 'Bearer ${Api.testToken}',
      }, body: {
        'assignment_id': assignmentId,
        'answers': jsonEncode(answerList.map((e) => e.toMap()).toList()),
      }).timeout(const Duration(seconds: 10));
      if (response.statusCode == 200) {
        print(response.body);
        Get.offAllNamed("/assignment-list/1");
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

  @override
  void onClose() {
    _timer?.cancel();
    super.onClose();
  }
}