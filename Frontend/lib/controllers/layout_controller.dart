import 'package:nega_lms/utils/imports.dart';

class LayoutController extends GetxController {
  late PageController pageController;
  late SidebarXController sidebarController;
  final RxString role = "".obs;
  final RxInt selectedClassId = 0.obs;

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
}
