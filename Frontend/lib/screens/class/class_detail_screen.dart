import 'package:nega_lms/utils/imports.dart';

class ClassDetailScreen extends GetView<ClassDetailController> {
  const ClassDetailScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Padding(
        padding: const EdgeInsets.fromLTRB(60, 20, 60, 0),
        child: Column(
          children: [
            Expanded(
              child: Obx(
                () => SizedBox(
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
                              [
                                "Lập trình",
                                controller.assignmentList[index].type ?? '',
                              ],
                              10,
                              index == controller.assignmentList.length - 1,
                              controller.assignmentList[index].id.toString(),
                            );
                          },
                        ),
                ),
              ),
            ),
          ],
        ),
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
                  Get.toNamed(Routes.doAssignmentScreen, arguments: {'assignment_id': id, 'class_id': controller.classId.value});
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
