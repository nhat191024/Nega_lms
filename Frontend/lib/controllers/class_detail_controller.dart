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
  RxInt assignmentDuration = 0.obs;
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
}
