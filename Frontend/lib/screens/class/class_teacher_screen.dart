import 'package:nega_lms/utils/imports.dart';

class ClassTeacherScreen extends GetView<ClassDetailController> {
  const ClassTeacherScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Padding(
            padding: const EdgeInsets.fromLTRB(10, 10, 0, 0),
            child: PopupMenuButton<String>(
              onSelected: (value) {
                if (value == 'Bộ câu hỏi (Quiz)') {
                  controller.assignmentType.value = 'quiz';
                  _showAddQuizModal(context);
                } else if (value == 'Bài tập 1 câu trả lời') {
                  controller.assignmentType.value = 'link';
                  _showAddLinkHomeworkModal(context);
                } else {
                  controller.assignmentType.value = 'quiz_bank';
                  _showAddQuizFromBankModal(context);
                  controller.createAssignmentThenPushToClass.value = true;
                }
              },
              itemBuilder: (BuildContext context) {
                return {'Bộ câu hỏi (Quiz)', 'Bài tập 1 câu trả lời', 'Bài tập từ kho quiz'}.map((String choice) {
                  return PopupMenuItem<String>(
                    value: choice,
                    textStyle: const TextStyle(
                      color: CustomColors.white,
                      fontFamily: FontStyleTextStrings.regular,
                      fontSize: 16,
                    ),
                    child: Text(choice),
                  );
                }).toList();
              },
              offset: const Offset(5, 45),
              color: CustomColors.primary,
              elevation: 0,
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(10),
              ),
              child: Container(
                padding: const EdgeInsets.symmetric(horizontal: 18, vertical: 12),
                decoration: BoxDecoration(
                  color: CustomColors.primary,
                  borderRadius: BorderRadius.circular(50),
                ),
                child: const Text(
                  "Tạo bài tập",
                  style: TextStyle(
                    fontSize: 16,
                    color: CustomColors.white,
                    fontFamily: FontStyleTextStrings.medium,
                  ),
                ),
              ),
            ),
          ),
          Padding(
            padding: const EdgeInsets.fromLTRB(60, 20, 60, 0),
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
                onTap: () {},
                btnText: 'Chỉnh sửa',
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

  Future _showAddQuizModal(context) {
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
              controller.clear();
              Get.back();
            }
          },
          child: Center(
            child: Material(
              color: Colors.transparent,
              child: Container(
                width: Get.width * 0.7,
                height: Get.height * 0.75,
                padding: const EdgeInsets.fromLTRB(20, 25, 20, 20),
                decoration: BoxDecoration(
                  color: CustomColors.white,
                  borderRadius: BorderRadius.circular(24),
                ),
                child: SingleChildScrollView(
                  child: Obx(
                    () => Column(
                      children: [
                        Row(
                          children: [
                            Expanded(
                              flex: 1,
                              child: CheckBoxField(
                                title: "Tạo thành bài tập và đẩy lên lớp",
                                value: controller.createAssignmentThenPushToClass.value.obs,
                                onChanged: (value) {
                                  controller.createAssignmentThenPushToClass.value = value!;
                                  if (value == false) {
                                    controller.isAssignmentDurationError.value = false;
                                    controller.isAssignmentAutoGrade.value = false;
                                    controller.isAssignmentStartDateError.value = false;
                                    controller.isAssignmentDueDateError.value = false;
                                    controller.assignmentDuration.text = '';
                                    controller.assignmentAutoGrade.value = '';
                                    controller.assignmentStartDate.text = '';
                                    controller.assignmentDueDate.text = '';
                                  }
                                },
                              ),
                            ),
                            const Expanded(
                              flex: 1,
                              child: Center(
                                child: Text(
                                  "Tạo bộ câu hỏi",
                                  style: TextStyle(
                                    fontSize: 22,
                                    color: CustomColors.primary,
                                    fontFamily: FontStyleTextStrings.medium,
                                  ),
                                ),
                              ),
                            ),
                            Expanded(
                              flex: 1,
                              child: Align(
                                alignment: Alignment.centerRight,
                                child: CustomButton(
                                  onTap: () => controller.createQuiz(),
                                  btnText: 'Tạo bài tập',
                                  btnColor: CustomColors.primary,
                                  width: 200,
                                ),
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 20),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            CustomTextField(
                              labelText: "Tên bài tập",
                              labelColor: CustomColors.primary,
                              labelSize: 18,
                              hintText: "nhập",
                              errorText: controller.assignmentNameError.value,
                              isError: controller.isAssignmentNameError.value.obs,
                              width: Get.width * 0.3,
                              obscureText: false.obs,
                              controller: controller.assignmentName,
                              onChanged: (value) {
                                if (value.isNotEmpty) {
                                  controller.isAssignmentNameError.value = false;
                                } else {
                                  controller.isAssignmentNameError.value = true;
                                  controller.assignmentNameError.value = "Câu hỏi không được để trống";
                                }
                              },
                            ),
                            CustomTextField(
                              labelText: "Chủ thể",
                              labelColor: CustomColors.primary,
                              labelSize: 18,
                              hintText: "nhập",
                              errorText: controller.assignmentSubjectError.value,
                              isError: controller.isAssignmentSubjectError.value.obs,
                              width: Get.width * 0.3,
                              obscureText: false.obs,
                              controller: controller.assignmentSubject,
                              onChanged: (value) {
                                if (value.isNotEmpty) {
                                  controller.isAssignmentSubjectError.value = false;
                                } else {
                                  controller.isAssignmentSubjectError.value = true;
                                  controller.assignmentSubjectError.value = "Chủ đề không được để trống";
                                }
                              },
                            ),
                          ],
                        ),
                        const SizedBox(height: 20),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            SelectBox(
                              labelText: "Trạng thái",
                              labelColor: CustomColors.primary,
                              labelSize: 18,
                              hintText: "Chọn",
                              errorText: controller.assignmentStatusError.value,
                              isError: controller.isAssignmentStatusError.value.obs,
                              width: Get.width * 0.3,
                              value: null,
                              items: const [
                                DropdownMenuItem(value: 'closed', child: Text('Closed')),
                                DropdownMenuItem(value: 'published', child: Text('Published')),
                                DropdownMenuItem(value: 'private', child: Text('Private')),
                                DropdownMenuItem(value: 'draft', child: Text('Draft')),
                              ],
                              onChanged: (value) {
                                if (value != null) {
                                  controller.assignmentStatus.value = value;
                                  controller.isAssignmentStatusError.value = false;
                                }
                              },
                            ),
                            SelectBox(
                              labelText: "Trình độ",
                              labelColor: CustomColors.primary,
                              labelSize: 18,
                              hintText: "Chọn",
                              errorText: controller.assignmentLevelError.value,
                              isError: controller.isAssignmentLevelError.value.obs,
                              width: Get.width * 0.3,
                              value: null,
                              items: const [
                                DropdownMenuItem(value: 'Trung học phổ thông', child: Text('Trung học phổ thông')),
                                DropdownMenuItem(value: 'Cao đẳng', child: Text('Cao đẳng')),
                                DropdownMenuItem(value: 'Đại học', child: Text('Đại học')),
                              ],
                              onChanged: (value) {
                                if (value != null) {
                                  controller.assignmentLevel.value = value;
                                  controller.isAssignmentLevelError.value = false;
                                }
                              },
                            ),
                          ],
                        ),
                        const SizedBox(height: 20),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            SelectBox(
                              labelText: "Chuyên ngành",
                              labelColor: CustomColors.primary,
                              labelSize: 18,
                              hintText: "Chọn",
                              errorText: controller.assignmentSpecializedError.value,
                              isError: controller.isAssignmentSpecializedError.value.obs,
                              width: Get.width * 0.3,
                              value: null,
                              items: const [
                                DropdownMenuItem(value: 'web', child: Text('Web')),
                                DropdownMenuItem(value: 'app', child: Text('App')),
                              ],
                              onChanged: (value) {
                                if (value != null) {
                                  controller.assignmentSpecialized.value = value;
                                  controller.isAssignmentSpecializedError.value = false;
                                }
                              },
                            ),
                            SelectBox(
                              labelText: "Topic",
                              labelColor: CustomColors.primary,
                              labelSize: 18,
                              hintText: "Chọn",
                              errorText: controller.assignmentTopicError.value,
                              isError: controller.isAssignmentTopicError.value.obs,
                              width: Get.width * 0.3,
                              value: null,
                              items: const [
                                DropdownMenuItem(value: 'php', child: Text('PHP')),
                                DropdownMenuItem(value: 'java', child: Text('Java')),
                                DropdownMenuItem(value: 'C#', child: Text('C#')),
                              ],
                              onChanged: (value) {
                                if (value != null) {
                                  controller.assignmentTopic.value = value;
                                  controller.isAssignmentTopicError.value = false;
                                }
                              },
                            ),
                          ],
                        ),
                        const SizedBox(height: 20),
                        if (controller.createAssignmentThenPushToClass.value == true) ...[
                          Row(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              CustomTextField(
                                labelText: "Thời gian làm bài",
                                labelColor: CustomColors.primary,
                                labelSize: 18,
                                hintText: "nhập",
                                errorText: controller.assignmentDurationError.value,
                                isError: controller.isAssignmentDurationError.value.obs,
                                width: Get.width * 0.3,
                                obscureText: false.obs,
                                controller: controller.assignmentDuration,
                                onChanged: (value) {
                                  if (value.isNotEmpty) {
                                    controller.isAssignmentDurationError.value = false;
                                  } else {
                                    controller.isAssignmentDurationError.value = true;
                                    controller.assignmentDurationError.value = "Thời gian làm bài không được để trống";
                                  }
                                },
                              ),
                              SelectBox(
                                labelText: "Tự động chấm điểm",
                                labelColor: CustomColors.primary,
                                labelSize: 18,
                                hintText: "Chọn",
                                errorText: controller.assignmentAutoGradeError.value,
                                isError: controller.isAssignmentAutoGrade.value.obs,
                                width: Get.width * 0.3,
                                value: null,
                                items: const [
                                  DropdownMenuItem(value: 'true', child: Text('Bật')),
                                  DropdownMenuItem(value: 'false', child: Text('Tắt')),
                                ],
                                onChanged: (value) {
                                  if (value != null) {
                                    controller.assignmentAutoGrade.value = value;
                                    controller.isAssignmentAutoGrade.value = false;
                                  }
                                },
                              ),
                            ],
                          ),
                          const SizedBox(height: 20),
                        ],
                        if (controller.createAssignmentThenPushToClass.value == true) ...[
                          Row(
                            children: [
                              DateTimeField(
                                labelText: "Thời gian bắt đầu",
                                labelColor: CustomColors.primary,
                                labelSize: 18,
                                hintText: "nhập",
                                errorText: controller.assignmentStartDateError.value,
                                isError: controller.isAssignmentStartDateError.value.obs,
                                width: Get.width * 0.3,
                                leftPadding: 55,
                                controller: controller.assignmentStartDate,
                                onChanged: (value) {
                                  if (value.isNotEmpty) {
                                    controller.isAssignmentStartDateError.value = false;
                                  } else {
                                    controller.isAssignmentStartDateError.value = true;
                                    controller.assignmentStartDateError.value = "Ngày bắt đầu không được để trống";
                                  }
                                },
                              ),
                              DateTimeField(
                                labelText: "Thời gian kết thúc",
                                labelColor: CustomColors.primary,
                                labelSize: 18,
                                hintText: "nhập",
                                errorText: controller.assignmentDueDateError.value,
                                isError: controller.isAssignmentDueDateError.value.obs,
                                width: Get.width * 0.3,
                                controller: controller.assignmentDueDate,
                                onChanged: (value) {
                                  if (value.isNotEmpty) {
                                    controller.isAssignmentDueDateError.value = false;
                                  } else {
                                    controller.isAssignmentDueDateError.value = true;
                                    controller.assignmentDueDateError.value = "Ngày kết thúc không được để trống";
                                  }
                                },
                              ),
                            ],
                          ),
                          const SizedBox(height: 20),
                        ],
                        CustomTextField(
                          labelText: "Mô tả",
                          labelColor: CustomColors.primary,
                          labelSize: 18,
                          hintText: "nhập",
                          errorText: controller.assignmentDescriptionError.value,
                          isError: controller.isAssignmentDescriptionError.value.obs,
                          width: Get.width * 0.62,
                          minLines: 4,
                          maxLines: 4,
                          obscureText: false.obs,
                          controller: controller.assignmentDescription,
                          onChanged: (value) {
                            if (value.isNotEmpty) {
                              controller.isAssignmentDescriptionError.value = false;
                            } else {
                              controller.isAssignmentDescriptionError.value = true;
                              controller.assignmentDescriptionError.value = "Mô tả không được để trống";
                            }
                          },
                        ),
                        const SizedBox(height: 40),
                        const Text(
                          "Câu hỏi",
                          style: TextStyle(
                            fontSize: 20,
                            color: CustomColors.primary,
                            fontFamily: FontStyleTextStrings.medium,
                          ),
                        ),
                        const SizedBox(height: 10),
                        Obx(
                          () => Column(
                            crossAxisAlignment: CrossAxisAlignment.center,
                            children: [
                              ...List.generate(
                                controller.questions.length,
                                (qIndex) => Padding(
                                  padding: const EdgeInsets.only(bottom: 20),
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.center,
                                    children: [
                                      Row(
                                        children: [
                                          IconButton(
                                            icon: const Icon(Icons.remove_circle_outline),
                                            color: CustomColors.primary,
                                            onPressed: () {
                                              controller.removeQuestion(qIndex);
                                            },
                                          ),
                                          Expanded(
                                            child: CustomTextField(
                                              labelText: "Câu hỏi ${qIndex + 1}",
                                              labelColor: CustomColors.primary,
                                              labelSize: 16,
                                              hintText: "Nhập câu hỏi",
                                              errorText: "",
                                              isError: false.obs,
                                              width: Get.width * 0.45,
                                              obscureText: false.obs,
                                              controller: controller.questions[qIndex]['question'],
                                              onChanged: (value) {},
                                            ),
                                          ),
                                          CustomTextField(
                                            labelText: "Điểm số",
                                            labelColor: CustomColors.primary,
                                            labelSize: 16,
                                            hintText: "Nhập điểm",
                                            errorText: "",
                                            isError: false.obs,
                                            width: Get.width * 0.1,
                                            obscureText: false.obs,
                                            controller: controller.questions[qIndex]['score'],
                                            keyboardType: TextInputType.number,
                                            disable: true,
                                            onChanged: (value) {},
                                          ),
                                          IconButton(
                                            icon: const Icon(Icons.add_circle_outline),
                                            color: CustomColors.primary,
                                            onPressed: () {
                                              controller.addAnswerToQuestion(qIndex);
                                            },
                                          ),
                                        ],
                                      ),
                                      const SizedBox(height: 10),
                                      Obx(
                                        () {
                                          final answers = controller.questions[qIndex]['answers'] as RxList<Map<String, dynamic>>;
                                          return Column(
                                            children: List.generate(
                                              answers.length,
                                              (aIndex) => Padding(
                                                padding: const EdgeInsets.only(left: 30, bottom: 10),
                                                child: Row(
                                                  children: [
                                                    Obx(() => Checkbox(
                                                          value: (answers[aIndex]['isCorrect'] as RxBool).value,
                                                          onChanged: (bool? value) {
                                                            controller.toggleAnswerCorrect(qIndex, aIndex);
                                                          },
                                                          activeColor: CustomColors.primary,
                                                        )),
                                                    Expanded(
                                                      child: CustomTextField(
                                                        labelText: "Câu trả lời ${aIndex + 1}",
                                                        labelColor: CustomColors.secondaryText,
                                                        labelSize: 14,
                                                        hintText: "Nhập câu trả lời",
                                                        errorText: "",
                                                        isError: false.obs,
                                                        width: Get.width * 0.5,
                                                        obscureText: false.obs,
                                                        controller: answers[aIndex]['controller'],
                                                        onChanged: (value) {},
                                                      ),
                                                    ),
                                                    IconButton(
                                                      icon: const Icon(Icons.remove_circle_outline),
                                                      color: CustomColors.primary,
                                                      onPressed: () {
                                                        controller.removeAnswer(qIndex, aIndex);
                                                      },
                                                    ),
                                                  ],
                                                ),
                                              ),
                                            ),
                                          );
                                        },
                                      ),
                                    ],
                                  ),
                                ),
                              ),
                              Center(
                                child: IconButton(
                                  icon: const Icon(Icons.add_circle_outline, size: 30),
                                  color: CustomColors.primary,
                                  onPressed: () => controller.addNewQuestion(),
                                ),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              ),
            ),
          ),
        );
      },
    );
  }

  Future _showAddLinkHomeworkModal(context) {
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
              controller.clear();
              Get.back();
            }
          },
          child: Center(
            child: Material(
              color: Colors.transparent,
              child: Container(
                width: Get.width * 0.7,
                height: Get.height * 0.65,
                padding: const EdgeInsets.fromLTRB(20, 25, 20, 20),
                decoration: BoxDecoration(
                  color: CustomColors.white,
                  borderRadius: BorderRadius.circular(24),
                ),
                child: Obx(
                  () => Column(
                    children: [
                      const Text(
                        "Tạo bài tập",
                        style: TextStyle(
                          fontSize: 22,
                          color: CustomColors.primary,
                          fontFamily: FontStyleTextStrings.medium,
                        ),
                      ),
                      const SizedBox(height: 20),
                      CustomTextField(
                        labelText: "Tên bài tập",
                        labelColor: CustomColors.primary,
                        labelSize: 18,
                        hintText: "nhập",
                        errorText: controller.assignmentNameError.value,
                        isError: controller.isAssignmentNameError.value.obs,
                        width: Get.width * 0.62,
                        obscureText: false.obs,
                        controller: controller.assignmentName,
                        onChanged: (value) {
                          if (value.isNotEmpty) {
                            controller.isAssignmentNameError.value = false;
                          } else {
                            controller.isAssignmentNameError.value = true;
                            controller.assignmentNameError.value = "Câu hỏi không được để trống";
                          }
                        },
                      ),
                      const SizedBox(height: 20),
                      Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          CustomTextField(
                            labelText: "Thời gian làm bài",
                            labelColor: CustomColors.primary,
                            labelSize: 18,
                            hintText: "nhập",
                            keyboardType: TextInputType.number,
                            errorText: controller.assignmentDurationError.value,
                            isError: controller.isAssignmentDurationError.value.obs,
                            width: Get.width * 0.3,
                            obscureText: false.obs,
                            controller: controller.assignmentDuration,
                            onChanged: (value) {
                              if (value.isNotEmpty) {
                                controller.isAssignmentDurationError.value = false;
                              } else {
                                controller.isAssignmentDurationError.value = true;
                                controller.assignmentDurationError.value = "Thời gian làm bài không được để trống";
                              }
                            },
                          ),
                          CustomTextField(
                            labelText: "Điểm số",
                            labelColor: CustomColors.primary,
                            labelSize: 18,
                            hintText: "nhập",
                            keyboardType: TextInputType.number,
                            errorText: controller.homeworkScoreError.value,
                            isError: controller.isHomeworkScoreError.value.obs,
                            width: Get.width * 0.3,
                            obscureText: false.obs,
                            controller: controller.homeworkScore,
                            onChanged: (value) {
                              if (value.isNotEmpty) {
                                controller.isHomeworkScoreError.value = false;
                              } else {
                                controller.isHomeworkScoreError.value = true;
                                controller.homeworkScoreError.value = "Điểm bài tập không được để trống";
                              }
                            },
                          ),
                        ],
                      ),
                      const SizedBox(height: 20),
                      Row(
                        children: [
                          DateTimeField(
                            labelText: "Thời gian bắt đầu",
                            labelColor: CustomColors.primary,
                            labelSize: 18,
                            hintText: "nhập",
                            errorText: controller.assignmentStartDateError.value,
                            isError: controller.isAssignmentStartDateError.value.obs,
                            width: Get.width * 0.3,
                            leftPadding: 55,
                            controller: controller.assignmentStartDate,
                            onChanged: (value) {
                              if (value.isNotEmpty) {
                                controller.isAssignmentStartDateError.value = false;
                              } else {
                                controller.isAssignmentStartDateError.value = true;
                                controller.assignmentStartDateError.value = "Ngày bắt đầu không được để trống";
                              }
                            },
                          ),
                          DateTimeField(
                            labelText: "Thời gian kết thúc",
                            labelColor: CustomColors.primary,
                            labelSize: 18,
                            hintText: "nhập",
                            errorText: controller.assignmentDueDateError.value,
                            isError: controller.isAssignmentDueDateError.value.obs,
                            width: Get.width * 0.3,
                            controller: controller.assignmentDueDate,
                            onChanged: (value) {
                              if (value.isNotEmpty) {
                                controller.isAssignmentDueDateError.value = false;
                              } else {
                                controller.isAssignmentDueDateError.value = true;
                                controller.assignmentDueDateError.value = "Ngày kết thúc không được để trống";
                              }
                            },
                          ),
                        ],
                      ),
                      const SizedBox(height: 20),
                      CustomTextField(
                        labelText: "Mô tả",
                        labelColor: CustomColors.primary,
                        labelSize: 18,
                        hintText: "nhập",
                        errorText: controller.assignmentDescriptionError.value,
                        isError: controller.isAssignmentDescriptionError.value.obs,
                        width: Get.width * 0.62,
                        minLines: 4,
                        maxLines: 4,
                        obscureText: false.obs,
                        controller: controller.assignmentDescription,
                        onChanged: (value) {
                          if (value.isNotEmpty) {
                            controller.isAssignmentDescriptionError.value = false;
                          } else {
                            controller.isAssignmentDescriptionError.value = true;
                            controller.assignmentDescriptionError.value = "Mô tả không được để trống";
                          }
                        },
                      ),
                      const Spacer(),
                      CustomButton(onTap: () => controller.createQuiz(), btnText: 'Tạo bài tập', btnColor: CustomColors.primary, width: 200),
                    ],
                  ),
                ),
              ),
            ),
          ),
        );
      },
    );
  }

  Future _showAddQuizFromBankModal(context) {
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
              controller.clear();
              Get.back();
            }
          },
          child: Center(
            child: Material(
              color: Colors.transparent,
              child: Container(
                width: Get.width * 0.7,
                height: Get.height * 0.75,
                padding: const EdgeInsets.fromLTRB(20, 25, 20, 20),
                decoration: BoxDecoration(
                  color: CustomColors.white,
                  borderRadius: BorderRadius.circular(24),
                ),
                child: SingleChildScrollView(
                  child: Obx(
                    () => Column(
                      children: [
                        Stack(
                          children: [
                            const Align(
                              alignment: Alignment.center,
                              child: Padding(
                                padding: EdgeInsets.only(top: 15),
                                child: Text(
                                  "Tạo bộ câu hỏi",
                                  style: TextStyle(
                                    fontSize: 22,
                                    color: CustomColors.primary,
                                    fontFamily: FontStyleTextStrings.medium,
                                  ),
                                ),
                              ),
                            ),
                            Align(
                              alignment: Alignment.centerRight,
                              child: CustomButton(
                                onTap: () => controller.createQuiz(),
                                btnText: 'Tạo bài tập',
                                btnColor: CustomColors.primary,
                                width: 200,
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 20),
                        CustomTextField(
                          labelText: "Tên bài tập",
                          labelColor: CustomColors.primary,
                          labelSize: 18,
                          hintText: "nhập",
                          errorText: controller.assignmentNameError.value,
                          isError: controller.isAssignmentNameError.value.obs,
                          width: Get.width * 0.62,
                          obscureText: false.obs,
                          controller: controller.assignmentName,
                          onChanged: (value) {
                            if (value.isNotEmpty) {
                              controller.isAssignmentNameError.value = false;
                            } else {
                              controller.isAssignmentNameError.value = true;
                              controller.assignmentNameError.value = "Câu hỏi không được để trống";
                            }
                          },
                        ),
                        const SizedBox(height: 20),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            CustomTextField(
                              labelText: "Thời gian làm bài",
                              labelColor: CustomColors.primary,
                              labelSize: 18,
                              hintText: "nhập",
                              errorText: controller.assignmentDurationError.value,
                              isError: controller.isAssignmentDurationError.value.obs,
                              width: Get.width * 0.3,
                              obscureText: false.obs,
                              controller: controller.assignmentDuration,
                              onChanged: (value) {
                                if (value.isNotEmpty) {
                                  controller.isAssignmentDurationError.value = false;
                                } else {
                                  controller.isAssignmentDurationError.value = true;
                                  controller.assignmentDurationError.value = "Thời gian làm bài không được để trống";
                                }
                              },
                            ),
                            SelectBox(
                              labelText: "Tự động chấm điểm",
                              labelColor: CustomColors.primary,
                              labelSize: 18,
                              hintText: "Chọn",
                              errorText: controller.assignmentAutoGradeError.value,
                              isError: controller.isAssignmentAutoGrade.value.obs,
                              width: Get.width * 0.3,
                              value: null,
                              items: const [
                                DropdownMenuItem(value: 'true', child: Text('Bật')),
                                DropdownMenuItem(value: 'false', child: Text('Tắt')),
                              ],
                              onChanged: (value) {
                                if (value != null) {
                                  controller.assignmentAutoGrade.value = value;
                                  controller.isAssignmentAutoGrade.value = false;
                                }
                              },
                            ),
                          ],
                        ),
                        const SizedBox(height: 20),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            CustomTextField(
                              labelText: "Điểm số",
                              labelColor: CustomColors.primary,
                              labelSize: 18,
                              hintText: "nhập",
                              errorText: controller.homeworkScoreError.value,
                              isError: controller.isHomeworkScoreError.value.obs,
                              width: Get.width * 0.3,
                              obscureText: false.obs,
                              controller: controller.homeworkScore,
                              onChanged: (value) {
                                if (value.isNotEmpty) {
                                  controller.isHomeworkScoreError.value = false;
                                } else {
                                  controller.isHomeworkScoreError.value = true;
                                  controller.homeworkScoreError.value = "Điểm số không được để trống";
                                }
                              },
                            ),
                            SelectBox(
                              labelText: "Trạng thái",
                              labelColor: CustomColors.primary,
                              labelSize: 18,
                              hintText: "Chọn",
                              errorText: controller.assignmentStatusError.value,
                              isError: controller.isAssignmentStatusError.value.obs,
                              width: Get.width * 0.3,
                              value: null,
                              items: const [
                                DropdownMenuItem(value: 'true', child: Text('Hiện')),
                                DropdownMenuItem(value: 'false', child: Text('Ẩn')),
                              ],
                              onChanged: (value) {
                                if (value != null) {
                                  controller.assignmentStatus.value = value;
                                  controller.isAssignmentStatusError.value = false;
                                }
                              },
                            ),
                          ],
                        ),
                        const SizedBox(height: 20),
                        Row(
                          children: [
                            DateTimeField(
                              labelText: "Thời gian bắt đầu",
                              labelColor: CustomColors.primary,
                              labelSize: 18,
                              hintText: "nhập",
                              errorText: controller.assignmentStartDateError.value,
                              isError: controller.isAssignmentStartDateError.value.obs,
                              width: Get.width * 0.3,
                              leftPadding: 55,
                              controller: controller.assignmentStartDate,
                              onChanged: (value) {
                                if (value.isNotEmpty) {
                                  controller.isAssignmentStartDateError.value = false;
                                } else {
                                  controller.isAssignmentStartDateError.value = true;
                                  controller.assignmentStartDateError.value = "Ngày bắt đầu không được để trống";
                                }
                              },
                            ),
                            DateTimeField(
                              labelText: "Thời gian kết thúc",
                              labelColor: CustomColors.primary,
                              labelSize: 18,
                              hintText: "nhập",
                              errorText: controller.assignmentDueDateError.value,
                              isError: controller.isAssignmentDueDateError.value.obs,
                              width: Get.width * 0.3,
                              controller: controller.assignmentDueDate,
                              onChanged: (value) {
                                if (value.isNotEmpty) {
                                  controller.isAssignmentDueDateError.value = false;
                                } else {
                                  controller.isAssignmentDueDateError.value = true;
                                  controller.assignmentDueDateError.value = "Ngày kết thúc không được để trống";
                                }
                              },
                            ),
                          ],
                        ),
                        const SizedBox(height: 20),
                        CustomTextField(
                          labelText: "Mô tả",
                          labelColor: CustomColors.primary,
                          labelSize: 18,
                          hintText: "nhập",
                          errorText: controller.assignmentDescriptionError.value,
                          isError: controller.isAssignmentDescriptionError.value.obs,
                          width: Get.width * 0.62,
                          minLines: 4,
                          maxLines: 4,
                          obscureText: false.obs,
                          controller: controller.assignmentDescription,
                          onChanged: (value) {
                            if (value.isNotEmpty) {
                              controller.isAssignmentDescriptionError.value = false;
                            } else {
                              controller.isAssignmentDescriptionError.value = true;
                              controller.assignmentDescriptionError.value = "Mô tả không được để trống";
                            }
                          },
                        ),
                      ],
                    ),
                  ),
                ),
              ),
            ),
          ),
        );
      },
    );
  }
}
