import 'package:nega_lms/utils/imports.dart';

class LayoutScreen extends GetView<LayoutController> {
  const LayoutScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        automaticallyImplyLeading: false,
        backgroundColor: Colors.transparent,
        elevation: 0,
        toolbarHeight: 80,
        titleSpacing: 0,
        title: const NavBar(),
      ),
      body: Row(
        children: [
          SideMenu(
            controller: controller.sideMenu,
            style: SideMenuStyle(
              displayMode: SideMenuDisplayMode.compact,
              backgroundColor: Colors.white,
              selectedColor: CustomColors.primary,
              unselectedIconColor: CustomColors.primaryText,
              unselectedTitleTextStyle: const TextStyle(color: CustomColors.primaryText),
              selectedTitleTextStyle: const TextStyle(color: Colors.white),
              decoration: const BoxDecoration(
                border: Border(right: BorderSide(color: CustomColors.border)),
              ),
            ),
            items: [
              SideMenuItem(
                title: 'Lớp học',
                icon: const Icon(Icons.class_),
                onTap: (index, _) {
                  controller.sideMenu.changePage(index);
                },
              ),
              SideMenuItem(
                title: 'Giảng viên',
                icon: const Icon(Icons.person),
                onTap: (index, _) {},
              ),
              SideMenuItem(
                title: 'Đăng nhập',
                icon: const Icon(Icons.login),
                onTap: (index, _) {
                  Get.toNamed(Routes.loginPage);
                },
              ),
            ],
          ),
          Expanded(
            child: PageView(
              controller: controller.pageController,
              children: [
                ClassListScreen(), // Màn hình Lớp học
                // TeacherPage(),
                // Thêm các màn hình khác ở đây
              ],
            ),
          ),
        ],
      ),
    );
  }
}
