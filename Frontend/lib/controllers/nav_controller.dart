import 'package:nega_lms/utils/imports.dart';

class NavController extends GetxController {
  final RxBool isLogin = false.obs;
  final RxString avatar = "".obs;

  @override
  void onInit() async {
    super.onInit();
    WidgetsBinding.instance.addPostFrameCallback((_) async {
      if (StorageService.checkData(key: "isLogin")) {
        isLogin.value = StorageService.readData(key: "isLogin") == "true" ? true : false;
        if (isLogin.value) avatar.value = StorageService.readData(key: "avatar");
      }
    });
  }
}
