import 'package:nega_lms/utils/imports.dart';

class CourseDetailController extends GetxController {
  RxBool isLoading = true.obs;
  RxString role = ''.obs;
  RxInt courseId = 0.obs;
  RxString courseCode = ''.obs;
  RxString courseName = ''.obs;
  RxString courseDescription = ''.obs;
  RxString teacherName = ''.obs;
  RxString token = "".obs;

  @override
  void onInit() {
    super.onInit();
    if (StorageService.checkData(key: "role")) {
      role.value = StorageService.readData(key: "role");
    }

    WidgetsBinding.instance.addPostFrameCallback((_) async {
      if (!await Token.checkToken()) return;
      token.value = await Token.getToken();
      courseId.value = Get.find<LayoutController>().selectedCourseId.value;
      await fetchCourseInfo(courseId.value);
    });
  }

  fetchCourseInfo(id) async {
    isLoading(true);
    try {
      String url = "${Api.server}courses/getById/$id";
      var response = await get(Uri.parse(url), headers: {
        'Authorization': 'Bearer $token',
      }).timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        var data = jsonDecode(response.body);
        courseCode.value = data['code'];
        courseName.value = data['name'];
        courseDescription.value = data['description'];
        isLoading(false);
      }
    } catch (e) {
      Get.snackbar("Error", "Failed to fetch class info");
    }
  }
}
