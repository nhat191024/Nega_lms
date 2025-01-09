import 'package:nega_lms/utils/imports.dart';

class LayoutController extends GetxController {
  late PageController pageController;
  late SideMenuController sideMenu;

  @override
  void onInit() {
    pageController = PageController();
    sideMenu = SideMenuController();

    sideMenu.addListener((index) {
      pageController.jumpToPage(index);
    });
    
    super.onInit();
  }
}
