import 'package:nega_lms/utils/imports.dart';

class CourseController extends GetxController {
  final TextEditingController searchController = TextEditingController();

  RxList<CourseModel> courseList = <CourseModel>[].obs;
  RxList<CourseModel> filteredList = <CourseModel>[].obs;
  RxBool isLoading = true.obs;
  RxString token = "".obs;

  @override
  void onInit() async {
    super.onInit();
    WidgetsBinding.instance.addPostFrameCallback((_) async {
      if (!await Token.checkToken()) return;
      token.value = await Token.getToken();
      await fetchCourse();
      filteredList.value = courseList;
    });
  }

  fetchCourse() async {
    try {
      isLoading.value = true;
      String url = "${Api.server}courses/get";
      var response = await get(Uri.parse(url), headers: {
        'Authorization': 'Bearer $token',
      }).timeout(const Duration(seconds: Api.apiTimeOut));

      if (response.statusCode == 200) {
        var data = jsonDecode(response.body);
        courseList.value = (data['courses'] as List).map((e) => CourseModel.fromJson(e)).toList();
      }
    } finally {
      isLoading.value = false;
    }
  }

  classFilter(String query) async {
    try {
      isLoading.value = true;
      filteredList.value = courseList.where((element) => element.name!.toLowerCase().contains(query.toLowerCase())).toList();
    } finally {
      isLoading.value = false;
    }
  }
}
