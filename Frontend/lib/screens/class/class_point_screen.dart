import 'package:nega_lms/utils/imports.dart';

class ClassPointScreen extends GetView<ClassDetailController> {
  const ClassPointScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Padding(
        padding: const EdgeInsets.fromLTRB(200, 40, 200, 40),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Column(
              children: [
                Row(
                  children: [
                    Container(
                      height: 100,
                      width: 100,
                      margin: const EdgeInsets.only(right: 10),
                      decoration: const BoxDecoration(
                        color: Colors.blueAccent,
                        shape: BoxShape.circle,
                      ),
                      clipBehavior: Clip.antiAlias,
                      child: controller.avatar.value.isEmpty
                          ? const Center(
                              child: Text(
                                'A',
                                style: TextStyle(
                                  color: Colors.white,
                                  fontSize: 20,
                                  fontFamily: FontStyleTextStrings.bold,
                                ),
                              ),
                            )
                          : Image.network(
                              //for development purpose
                              "https://van191024.xyz/upload/avt/default.png",
                              fit: BoxFit.cover,
                            ),
                    ),
                    const SizedBox(width: 20),
                    Text(
                      controller.username.value,
                      style: const TextStyle(
                        fontSize: 26,
                        color: CustomColors.primary,
                        fontFamily: FontStyleTextStrings.bold,
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 20),
                const Divider(thickness: 1, color: CustomColors.primary),
              ],
            ),
            SingleChildScrollView(
              child: Obx(
                () => controller.isLoading.value
                    ? const Center(
                        child: CircularProgressIndicator(),
                      )
                    : Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        mainAxisAlignment: MainAxisAlignment.start,
                        children: [
                          ListView.builder(
                            shrinkWrap: true,
                            physics: const AlwaysScrollableScrollPhysics(),
                            itemCount: controller.studentPointList.length,
                            itemBuilder: (context, index) {
                              return pointBuilder(
                                context,
                                controller.studentPointList[index]['title'] ?? '',
                                controller.studentPointList[index]['due_date'] ?? '',
                                controller.studentPointList[index]['score'].toString(),
                                controller.studentPointList[index]['total_score'].toString(),
                                controller.studentPointList[index]['type'] ?? '',
                                10,
                                controller.studentPointList[index]['handed_in'] ?? false,
                                index == controller.classPointList.length - 1,
                              );
                            },
                          ),
                        ],
                      ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  //class card builder
  Widget pointBuilder(
    context,
    String title,
    String dueDate,
    String point,
    String totalPoint,
    String type,
    double verticalPadding,
    bool isHanded,
    bool isLast,
  ) {
    return Padding(
      padding: EdgeInsets.symmetric(vertical: verticalPadding),
      child: Column(
        children: [
          Row(
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              Padding(
                padding: const EdgeInsets.only(left: 100),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      '$title - $type',
                      style: const TextStyle(
                        fontSize: 20,
                        color: CustomColors.primary,
                        fontFamily: FontStyleTextStrings.medium,
                      ),
                    ),
                    Text(
                      dueDate,
                      style: const TextStyle(
                        fontSize: 16,
                        color: CustomColors.secondaryText,
                        fontFamily: FontStyleTextStrings.regular,
                      ),
                    ),
                  ],
                ),
              ),
              const Spacer(),
              Padding(
                padding: const EdgeInsets.only(right: 100),
                child: Column(
                  children: [
                    if (type == 'lab') ...[
                      if (isHanded)
                        const Text(
                          'Đã nộp',
                          style: TextStyle(
                            fontSize: 18,
                            color: CustomColors.primary,
                            fontFamily: FontStyleTextStrings.medium,
                          ),
                        )
                      else
                        const Text(
                          'Chưa nộp',
                          style: TextStyle(
                            fontSize: 18,
                            color: CustomColors.primary,
                            fontFamily: FontStyleTextStrings.medium,
                          ),
                        ),
                    ] else ...[
                      if (isHanded)
                        Text(
                          'Điểm: $point/$totalPoint',
                          style: const TextStyle(
                            fontSize: 18,
                            color: CustomColors.primary,
                            fontFamily: FontStyleTextStrings.medium,
                          ),
                        )
                      else
                        const Text(
                          'Chưa hoàn thành',
                          style: TextStyle(
                            fontSize: 18,
                            color: CustomColors.primary,
                            fontFamily: FontStyleTextStrings.medium,
                          ),
                        ),
                    ]
                  ],
                ),
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
