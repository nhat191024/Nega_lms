import 'package:nega_lms/utils/imports.dart';

class ClassAssignmentScreen extends GetView<ClassDetailController> {
  const ClassAssignmentScreen({super.key});

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
                              context,
                              controller.assignmentList[index].title ?? '',
                              controller.assignmentList[index].description ?? '',
                              controller.assignmentList[index].duration ?? '',
                              10,
                              index == controller.assignmentList.length - 1,
                              controller.assignmentList[index].id.toString(),
                              controller.assignmentList[index].type ?? '',
                              controller.assignmentList[index].isSubmitted ?? false,
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
  Widget classCardBuilder(
    context,
    String title,
    String description,
    String duration,
    double verticalPadding,
    bool isLast,
    String id,
    String type,
    bool isSubmitted,
  ) {
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
                    Row(
                      children: [
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                          margin: const EdgeInsets.only(right: 10),
                          decoration: BoxDecoration(
                            color: CustomColors.primary,
                            borderRadius: BorderRadius.circular(50),
                          ),
                          child: Text(
                            "Loại bài tập: $type",
                            style: const TextStyle(
                              fontSize: 12,
                              color: CustomColors.background,
                              fontFamily: FontStyleTextStrings.medium,
                            ),
                          ),
                        ),
                        const SizedBox(width: 10),
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                          margin: const EdgeInsets.only(right: 10),
                          decoration: BoxDecoration(
                            color: CustomColors.primary,
                            borderRadius: BorderRadius.circular(50),
                          ),
                          child: Text(
                            "Thời gian làm: $duration",
                            style: const TextStyle(
                              fontSize: 12,
                              color: CustomColors.background,
                              fontFamily: FontStyleTextStrings.medium,
                            ),
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
              const SizedBox(width: 20),
              CustomButton(
                onTap: () {
                  if (isSubmitted) {
                    Get.dialog(
                      const NotificationDialogWithoutButton(title: "Thông báo", message: "Bạn đã nộp bài tập này rồi"),
                    );
                    return;
                  }
                  if (type == 'quiz') {
                    Get.toNamed(Routes.doAssignmentScreen, arguments: {'assignment_id': id, 'class_id': controller.classId.value});
                  } else {
                    _showLinkSubmitModal(context, title, description, type, id);
                  }
                },
                btnText: type == 'quiz' ? 'Làm bài tập' : 'Nộp bài',
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

  Future _showLinkSubmitModal(context, String title, String description, String type, String id) {
    return showGeneralDialog(
      context: context,
      barrierColor: Colors.black.withOpacity(0.5),
      barrierDismissible: true,
      barrierLabel: MaterialLocalizations.of(context).modalBarrierDismissLabel,
      pageBuilder: (context, animation, secondaryAnimation) {
        return PopScope(
          canPop: true,
          onPopInvokedWithResult: (didPop, result) {
            if (didPop) {
              controller.linkSubmit.clear();
              Get.back();
            }
          },
          child: Center(
            child: Material(
              color: Colors.transparent,
              child: Container(
                width: Get.width * 0.5,
                height: Get.height * 0.3,
                padding: const EdgeInsets.fromLTRB(20, 25, 20, 20),
                decoration: BoxDecoration(
                  color: CustomColors.white,
                  borderRadius: BorderRadius.circular(24),
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.center,
                  children: [
                    const Text(
                      'Nộp bài tập',
                      style: TextStyle(
                        fontSize: 24,
                        color: CustomColors.primary,
                        fontFamily: FontStyleTextStrings.bold,
                      ),
                    ),
                    const SizedBox(height: 10),
                    ConstrainedBox(
                      constraints: BoxConstraints(maxWidth: Get.width * 0.4),
                      child: Text(
                        title,
                        style: const TextStyle(
                          fontSize: 18,
                          color: CustomColors.primaryText,
                          fontFamily: FontStyleTextStrings.medium,
                        ),
                        maxLines: 4,
                        softWrap: true,
                      ),
                    ),
                    const SizedBox(height: 10),
                    ConstrainedBox(
                      constraints: BoxConstraints(maxWidth: Get.width * 0.4),
                      child: Text(
                        description,
                        style: const TextStyle(
                          fontSize: 16,
                          color: CustomColors.secondaryText,
                          fontFamily: FontStyleTextStrings.regular,
                        ),
                        maxLines: 4,
                        softWrap: true,
                      ),
                    ),
                    const SizedBox(height: 20),
                    Obx(
                      () => CustomTextField(
                        labelText: "Link bài tập",
                        labelColor: CustomColors.primary,
                        labelSize: 18,
                        hintText: "nhập",
                        errorText: controller.linkSubmitError.value,
                        isError: controller.isLinkSubmitError.value.obs,
                        width: Get.width * 0.4,
                        obscureText: false.obs,
                        controller: controller.linkSubmit,
                        onChanged: (value) {
                          if (value.isNotEmpty) {
                            controller.isLinkSubmitError.value = false;
                          } else {
                            controller.isLinkSubmitError.value = true;
                            controller.linkSubmitError.value = "Link nộp bài được để trống";
                          }
                        },
                      ),
                    ),
                    const Spacer(),
                    CustomButton(
                      onTap: () {
                        if (controller.linkSubmit.text.isNotEmpty) {
                          controller.submitAssignment(type, id);
                          Get.back();
                        } else {
                          controller.isLinkSubmitError.value = true;
                          controller.linkSubmitError.value = "Link nộp bài được để trống";
                        }
                      },
                      btnText: 'Nộp bài',
                      btnColor: CustomColors.primary,
                      width: 140,
                    ),
                  ],
                ),
              ),
            ),
          ),
        );
      },
    );
  }
}
