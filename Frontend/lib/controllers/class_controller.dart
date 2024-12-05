import 'package:nega_lms/utils/imports.dart';

class ClassController extends GetxController {
  final TextEditingController searchController = TextEditingController();
  RxList<ClassModel> classList = <ClassModel>[].obs;
  RxBool isLoading = true.obs;

  @override
  void onInit() {
    fetchClass();
    super.onInit();
  }

  fetchClass() async {
    try {
      isLoading.value = true;
      String url = "${Api.server}classes";
      String token = "1|6U8SJLyqdph9xxCu8abxA0l4KEQYLsLOWwGx8jd97791ae46";
      var response = await get(Uri.parse(url), headers: {
        'Authorization': 'Bearer $token',
      }).timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        var data = jsonDecode(response.body);
        classList.value = (data['classes'] as List).map((e) => ClassModel.fromJson(e)).toList();
      }
    } finally {
      isLoading.value = false;
    }
  }
}
