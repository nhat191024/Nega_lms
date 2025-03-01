import 'package:nega_lms/utils/imports.dart';

class ClassDetailTab extends GetView<ClassDetailController> {
  const ClassDetailTab({super.key});

  @override
  Widget build(BuildContext context) {
    Get.put(ClassDetailController());
    return Scaffold(
      body: Row(
        children: [
          Expanded(
            child: Column(
              children: [
                Row(
                  children: [
                    SizedBox(
                      width: Get.width * 0.25,
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
                        tabs: [
                          const Tab(text: 'Tổng quan'),
                          if (controller.role.value == "student") const Tab(text: 'Bài tập'),
                          if (controller.role.value == "teacher") const Tab(text: 'Giảng viên'),
                          const Tab(text: 'Điểm'),
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
                    if (controller.role.value == "teacher")
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
                  child: TabBarView(
                    controller: controller.tabController,
                    children: [
                      ClassOverviewScreen(),
                      if (controller.role.value == "student") const ClassAssignmentScreen(),
                      if (controller.role.value == "teacher") const ClassTeacherScreen(),
                      if (controller.role.value == "student") const ClassPointScreen(),
                      if (controller.role.value == "teacher") const ClassPointOverviewScreen(),
                    ],
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
