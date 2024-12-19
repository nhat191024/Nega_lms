import 'package:nega_lms/utils/imports.dart';

class ClassController extends GetxController {
  final TextEditingController searchController = TextEditingController();
  RxList<ClassModel> classList = <ClassModel>[].obs;
  RxList<ClassModel> filteredList = <ClassModel>[].obs;
  RxBool isLoading = true.obs;
  RxBool isJoinBtnLoading = false.obs;
  RxString token = "".obs;

  @override
  void onInit() async {
    super.onInit();
    WidgetsBinding.instance.addPostFrameCallback((_) async {
      if (!await Token.checkToken()) return;
      token.value = await Token.getToken();
      await fetchClass();
      filteredList.value = classList;
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

  joinClass(int classId) async {
    try {
      isJoinBtnLoading.value = true;
      String url = "${Api.server}classes/join/$classId";
      var response = await get(Uri.parse(url), headers: {
        'Authorization': 'Bearer $token',
      }).timeout(const Duration(seconds: Api.apiTimeOut));

      var data = jsonDecode(response.body);

      if (response.statusCode == 201) {
        isJoinBtnLoading.value = false;
        Get.dialog(
          NotificationDialogWithoutButton(
            title: 'Thành công',
            message: data['message'],
          ),
        );

        await fetchClass();
      } else if (response.statusCode == 409) {
        isJoinBtnLoading.value = false;
        Get.dialog(
          NotificationDialogWithoutButton(
            title: 'Thất bại',
            message: data['message'],
          ),
        );
      }
    }
    catch (e) {
      isJoinBtnLoading.value = false;
      Get.dialog(
        const NotificationDialogWithoutButton(
          title: 'Thất bại',
          message: 'Đã có lỗi xảy ra, vui lòng thử lại sau',
        ),
      );
    }
    finally {
      isJoinBtnLoading.value = false;
    }
  }

  classFilter(String query) async {
    try {
      isLoading.value = true;
      String url = "${Api.server}classes/search/$query";
      var response = await get(Uri.parse(url), headers: {
        'Authorization': 'Bearer $token',
      }).timeout(const Duration(seconds: Api.apiTimeOut));

      if (response.statusCode == 200) {
        var data = jsonDecode(response.body);
        filteredList.value = (data['classes'] as List).map((e) => ClassModel.fromJson(e)).toList();
      } else if (response.statusCode == 404) {
        filteredList.value = classList.where((element) => element.name!.toLowerCase().contains(query.toLowerCase())).toList();
      } else {
        Get.dialog(
          const NotificationDialogWithoutButton(
            title: 'Thất bại',
            message: 'Đã có lỗi xảy ra, vui lòng thử lại sau',
          ),
        );
      }
    } finally {
      isLoading.value = false;
    }
  }
}
