import 'package:nega_lms/utils/imports.dart';

class LayoutController extends GetxController {
  late PageController pageController;
  late SidebarXController sidebarController;
  final RxString role = "".obs;
  final RxInt selectedClassId = 0.obs;
  final RxInt selectedCourseId = 0.obs;

  @override
  void onInit() {
    pageController = PageController();
    sidebarController = SidebarXController(selectedIndex: 0, extended: false);
    if (StorageService.checkData(key: "role")) {
      role.value = StorageService.readData(key: "role");
    }
    super.onInit();
  }

  void goToClassDetail(int classId) {
    selectedClassId.value = classId;
    pageController.jumpToPage(1);
  }

  void goToCourseDetail(int courseId) {
    selectedCourseId.value = courseId;
    pageController.jumpToPage(1);
  }

  logout() async {
    var url = Uri.parse("${Api.server}logout");
    try {
      var token = await Token.getToken();
      var response = await get(url, headers: {"Authorization": "Bearer $token"});
      if (response.statusCode == 200) {
        Token.removeToken();
        StorageService.removeData(key: "username");
        StorageService.removeData(key: "avatar");
        StorageService.removeData(key: "isLogin");
        StorageService.removeData(key: "role");
        Get.offAllNamed(Routes.loginPage);
      }
    } catch (e) {
      Get.dialog(
        const NotificationDialog(
          title: "Đăng xuất thất bại",
          message: "Đã xảy ra lỗi, vui lòng thử lại sau",
        ),
      );
    }
  }
}
