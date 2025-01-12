import 'package:nega_lms/utils/imports.dart';

class LayoutScreen extends GetView<LayoutController> {
  const LayoutScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final isMobile = MediaQuery.of(context).size.width < 768;
    final GlobalKey<ScaffoldState> scaffoldKey = GlobalKey<ScaffoldState>();
    return Scaffold(
      key: scaffoldKey,
      appBar: isMobile
          ? AppBar(
              automaticallyImplyLeading: false,
              backgroundColor: Colors.transparent,
              elevation: 0,
              toolbarHeight: 80,
              titleSpacing: 0,
              title: NavBar(
                scaffoldKey: scaffoldKey,
              ),
            )
          : AppBar(
              automaticallyImplyLeading: false,
              backgroundColor: Colors.transparent,
              elevation: 0,
              toolbarHeight: 80,
              titleSpacing: 0,
              title: NavBar(
                scaffoldKey: scaffoldKey,
              ),
            ),
      drawer: SidebarX(
        showToggleButton: false,
        controller: controller.sidebarController,
        theme: SidebarXTheme(
          textStyle: const TextStyle(
            color: CustomColors.primaryText,
            fontSize: 16,
            fontFamily: FontStyleTextStrings.regular,
          ),
          selectedTextStyle: const TextStyle(
            color: CustomColors.white,
            fontSize: 16,
            fontFamily: FontStyleTextStrings.medium,
          ),
          itemTextPadding: const EdgeInsets.symmetric(horizontal: 20),
          selectedItemTextPadding: const EdgeInsets.symmetric(horizontal: 20),
          selectedItemDecoration: BoxDecoration(
            color: CustomColors.primary,
            borderRadius: BorderRadius.circular(10),
            border: Border.all(
              color: CustomColors.border,
            ),
          ),
          selectedIconTheme: const IconThemeData(
            color: CustomColors.white,
          ),
          decoration: const BoxDecoration(
            color: CustomColors.background,
            border: Border(
              right: BorderSide(
                color: CustomColors.border,
                width: 1,
              ),
            ),
          ),
        ),
        extendedTheme: const SidebarXTheme(
          width: 300,
        ),
        headerBuilder: (context, extended) {
          return Container(
            padding: const EdgeInsets.only(top: 20),
            height: 100,
            child: Center(
              child: Image.asset(Images.logoNoBg, width: extended ? 150 : 50),
            ),
          );
        },
        footerDivider: const Divider(color: CustomColors.dividers, height: 1),
        footerItems: [
          SidebarXItem(
            icon: Icons.logout,
            label: 'Đăng xuất',
            onTap: () {},
          ),
        ],
        items: const [
          SidebarXItem(
            icon: Icons.home,
            label: 'Trang chủ',
          ),
          SidebarXItem(
            icon: Icons.school,
            label: 'Lớp học',
          ),
          SidebarXItem(
            icon: Icons.person,
            label: 'Giáo viên',
          ),
          // Thêm các mục khác ở đây
        ],
      ),
      body: Row(
        children: [
          if (!isMobile)
            SidebarX(
              showToggleButton: true,
              controller: controller.sidebarController,
              animationDuration: const Duration(milliseconds: 150),
              theme: SidebarXTheme(
                textStyle: const TextStyle(
                  color: CustomColors.primaryText,
                  fontSize: 16,
                  fontFamily: FontStyleTextStrings.regular,
                ),
                selectedTextStyle: const TextStyle(
                  color: CustomColors.white,
                  fontSize: 16,
                  fontFamily: FontStyleTextStrings.medium,
                ),
                itemTextPadding: const EdgeInsets.symmetric(horizontal: 20),
                selectedItemTextPadding: const EdgeInsets.symmetric(horizontal: 20),
                selectedItemDecoration: BoxDecoration(
                  color: CustomColors.primary,
                  borderRadius: BorderRadius.circular(10),
                  border: Border.all(
                    color: CustomColors.border,
                  ),
                ),
                selectedIconTheme: const IconThemeData(
                  color: CustomColors.white,
                ),
                decoration: const BoxDecoration(
                  color: CustomColors.background,
                  border: Border(
                    right: BorderSide(
                      color: CustomColors.border,
                      width: 1,
                    ),
                  ),
                ),
              ),
              extendedTheme: const SidebarXTheme(
                width: 300,
              ),
              headerBuilder: (context, extended) {
                return Container(
                  padding: const EdgeInsets.only(top: 20),
                  height: 100,
                  child: Center(
                    child: Image.asset(Images.logoNoBg, width: extended ? 150 : 50),
                  ),
                );
              },
              footerDivider: const Divider(color: CustomColors.dividers, height: 1),
              footerItems: [
                SidebarXItem(
                  icon: Icons.logout,
                  label: 'Đăng xuất',
                  onTap: () => controller.logout(),
                ),
              ],
              items: [
                const SidebarXItem(
                  icon: Icons.school,
                  label: 'Lớp học',
                ),
                const SidebarXItem(
                  icon: Icons.person,
                  label: 'Lớp học của tôi',
                ),
                if (controller.role.value == "teacher")
                  const SidebarXItem(
                    icon: Icons.person,
                    label: 'Giáo viên',
                  ),
                // Thêm các mục khác ở đây
              ],
            ),
          Expanded(
            child: PageView(
              controller: controller.pageController,
              children: [
                ClassListScreen(),
                const ClassDetailTab(),
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
