import 'package:nega_lms/utils/imports.dart';

class ClassDetailScreen extends GetView<AssignmentController> {
  const ClassDetailScreen({super.key});

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
            child: SingleChildScrollView(
              child: Column(
                children: [
                  Padding(
                    padding: const EdgeInsets.fromLTRB(100, 60, 200, 0),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.stretch,
                      children: [
                        Row(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Expanded(
                              child: Column(
                                children: [
                                  Obx(() => SizedBox(
                                        height: Get.height * 0.8,
                                        child: controller.isLoading.value
                                            ? const Center(
                                                child: CircularProgressIndicator(),
                                              )
                                            : ListView.builder(
                                                shrinkWrap: true,
                                                physics: const AlwaysScrollableScrollPhysics(),
                                                itemCount: controller.assignmentList.length,
                                                itemBuilder: (context, index) {
                                                  return classCardBuilder(
                                                    controller.assignmentList[index].name ?? '',
                                                    controller.assignmentList[index].description ?? '',
                                                    ["Lập trình"],
                                                    10,
                                                    index == controller.assignmentList.length - 1,
                                                    // controller.assignmentList[index].id.toString(),
                                                    '1',
                                                  );
                                                },
                                              ),
                                      )),
                                ],
                              ),
                            ),
                          ],
                        )
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  //class card builder
  Widget classCardBuilder(String title, String description, List<String> tags, double verticalPadding, bool isLast, String id) {
    return Padding(
      padding: EdgeInsets.symmetric(vertical: verticalPadding),
      child: Column(
        children: [
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      title,
                      style: const TextStyle(
                        fontSize: 16,
                        color: CustomColors.primaryText,
                        fontFamily: FontStyleTextStrings.medium,
                      ),
                    ),
                    const SizedBox(height: 5),
                    Text(
                      description,
                      style: const TextStyle(
                        fontSize: 14,
                        color: CustomColors.secondaryText,
                        fontFamily: FontStyleTextStrings.regular,
                      ),
                    ),
                    const SizedBox(height: 20),
                    Wrap(
                      children: tags
                          .map(
                            (tag) => Container(
                              padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                              margin: const EdgeInsets.only(right: 10),
                              decoration: BoxDecoration(
                                color: CustomColors.primary,
                                borderRadius: BorderRadius.circular(50),
                              ),
                              child: Text(
                                tag,
                                style: const TextStyle(
                                  fontSize: 12,
                                  color: CustomColors.background,
                                  fontFamily: FontStyleTextStrings.medium,
                                ),
                              ),
                            ),
                          )
                          .toList(),
                    ),
                  ],
                ),
              ),
              const SizedBox(width: 20),
              CustomButton(
                onTap: () {
                  controller.loadAssignment(id);
                },
                btnText: 'Làm bài tập',
                btnColor: CustomColors.primary,
                width: 140,
              ),
            ],
          ),
          const SizedBox(height: 20),
          if (!isLast) const Divider(),
        ],
      ),
    );
  }
}
