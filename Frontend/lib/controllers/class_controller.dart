import 'package:nega_lms/utils/imports.dart';

class ClassController extends GetxController {
  final TextEditingController searchController = TextEditingController();
  RxList<ClassModel> classList = <ClassModel>[].obs;
  RxBool isLoading = true.obs;
  RxString token = "".obs;

  @override
  void onInit() async {
    super.onInit();
    WidgetsBinding.instance.addPostFrameCallback((_) async {
      if (!await Token.checkToken()) return;
      token.value = await Token.getToken();
      await fetchClass();
    });
  }

  fetchClass() async {
    try {
      isLoading.value = true;
      String url = "${Api.server}classes";
      var response = await get(Uri.parse(url), headers: {
        'Authorization': 'Bearer $token',
      }).timeout(const Duration(seconds: Api.apiTimeOut));

      if (response.statusCode == 200) {
        var data = jsonDecode(response.body);
        classList.value = (data['classes'] as List).map((e) => ClassModel.fromJson(e)).toList();
      }
    } finally {
      isLoading.value = false;
    }
  }

  classFilter(String query) {
    return classList.where((element) => element.name!.toLowerCase().contains(query.toLowerCase())).toList();
  }
}
