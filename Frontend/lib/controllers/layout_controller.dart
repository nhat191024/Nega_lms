import 'package:nega_lms/utils/imports.dart';

class LayoutController extends GetxController {
  late PageController pageController;
  late SidebarXController sidebarController;

  @override
  void onInit() {
    pageController = PageController();
    sidebarController = SidebarXController(selectedIndex: 0, extended: false);

    sidebarController.addListener(() {
      pageController.jumpToPage(sidebarController.selectedIndex);
    });

    super.onInit();
  }
}
