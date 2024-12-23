import 'package:nega_lms/utils/imports.dart';

class ClassPointScreen extends GetView<ClassDetailController> {
  const ClassPointScreen({super.key});

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
                          itemCount: controller.classPointList.length,
                          itemBuilder: (context, index) {
                            return pointBuilder(
                              context,
                              controller.classPointList[index]['assignment_name'] ?? '',
                              controller.classPointList[index]['student_name'] ?? '',
                              controller.classPointList[index]['created_at'] ?? '',
                              controller.classPointList[index]['total_score'] == null
                                  ? 'Chưa chấm điểm'
                                  : controller.classPointList[index]['total_score'].toString(),
                              10,
                              index == controller.assignmentList.length - 1,
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
  Widget pointBuilder(context, String title, String studentName, String createAt, String point, double verticalPadding, bool isLast) {
    return Padding(
      padding: EdgeInsets.symmetric(vertical: verticalPadding),
      child: Column(
        children: [
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    "Bài tập: $title",
                    style: const TextStyle(
                      fontSize: 18,
                      color: CustomColors.primary,
                      fontFamily: FontStyleTextStrings.bold,
                    ),
                  ),
                  const SizedBox(height: 5),
                  Text(
                    "Người làm: $studentName",
                    style: const TextStyle(
                      fontSize: 16,
                      color: CustomColors.primaryText,
                      fontFamily: FontStyleTextStrings.medium,
                    ),
                  ),
                  const SizedBox(height: 5),
                  Text(
                    "Ngày làm: $createAt",
                    style: const TextStyle(
                      fontSize: 16,
                      color: CustomColors.primaryText,
                      fontFamily: FontStyleTextStrings.medium,
                    ),
                  ),
                ],
              ),
              const SizedBox(width: 50),
              Text(
                "Điểm: $point",
                style: const TextStyle(
                  fontSize: 40,
                  color: CustomColors.primary,
                  fontFamily: FontStyleTextStrings.medium,
                ),
              ),
              const Spacer(),
              CustomButton(
                onTap: () {},
                btnText: 'Xem',
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
