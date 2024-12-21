import 'package:nega_lms/utils/imports.dart';

class ClassDetailTab extends GetView<ClassDetailController> {
  const ClassDetailTab({super.key});

  @override
  Widget build(BuildContext context) {
    final ValueNotifier<bool> isCollapsed = ValueNotifier<bool>(true);

    return Scaffold(
      appBar: AppBar(
        backgroundColor: Colors.transparent,
        elevation: 0,
        toolbarHeight: 80,
        titleSpacing: 0,
        title: NavBar(
          onMenuPressed: () {
            isCollapsed.value = !isCollapsed.value;
          },
          showMenuButton: true,
        ),
      ),
      body: Row(
        children: [
          ValueListenableBuilder<bool>(
            valueListenable: isCollapsed,
            builder: (context, value, child) {
              return SideBar(isCollapsed: value);
            },
          ),
          Expanded(
            child: Column(
              children: [
                Row(
                  children: [
                    SizedBox(
                      width: Get.width * 0.2,
                      child: TabBar(
                        controller: controller.tabController,
                        labelColor: CustomColors.primary,
                        unselectedLabelColor: CustomColors.secondaryText,
                        labelStyle: const TextStyle(
                          fontFamily: FontStyleTextStrings.medium,
                          fontSize: 16,
                        ),
                        indicatorWeight: 4,
                        indicatorColor: CustomColors.primary,
                        indicatorSize: TabBarIndicatorSize.label,
                        tabs: const [
                          Tab(text: 'Tổng quan'),
                          Tab(text: 'Bài giảng'),
                          Tab(text: 'Thảo luận'),
                        ],
                      ),
                    ),
                    const Spacer(),
                    Obx(
                      () => Row(
                        children: [
                          RichText(
                            text: TextSpan(
                              text: 'Mã lớp: ',
                              style: const TextStyle(
                                color: CustomColors.primaryText,
                                fontSize: 18,
                                fontFamily: FontStyleTextStrings.medium,
                              ),
                              children: [
                                TextSpan(
                                  text: controller.classCode.value,
                                  style: const TextStyle(
                                    color: CustomColors.primary,
                                    fontSize: 18,
                                    fontFamily: FontStyleTextStrings.medium,
                                  ),
                                ),
                              ],
                            ),
                          ),
                          const SizedBox(width: 20),
                          Text(
                            "Lớp: ${controller.className.value}",
                            style: const TextStyle(
                              color: CustomColors.primaryText,
                              fontSize: 18,
                              fontFamily: FontStyleTextStrings.medium,
                            ),
                          ),
                          const SizedBox(width: 20),
                          Text(
                            "Giảng viên: ${controller.teacherName.value}",
                            style: const TextStyle(
                              color: CustomColors.primaryText,
                              fontSize: 18,
                              fontFamily: FontStyleTextStrings.medium,
                            ),
                          ),
                        ],
                      ),
                    ),
                    IconButton(
                      onPressed: () {},
                      icon: const Icon(
                        Icons.settings,
                        color: CustomColors.primary,
                      ),
                    ),
                    const SizedBox(width: 40),
                  ],
                ),
                Expanded(
                  child: Padding(
                    padding: const EdgeInsets.fromLTRB(60, 20, 60, 0),
                    child: TabBarView(
                      controller: controller.tabController,
                      children: const [
                        ClassDetailScreen(),
                        ClassDetailScreen(),
                        ClassDetailScreen(),
                      ],
                    ),
                  ),
                )
              ],
            ),
          ),
        ],
      ),
    );
  }
}
